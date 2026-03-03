<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\PessoaFisica;
use App\Models\PessoaJuridica;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Services\ReceitaWsService;
use App\Support\DocValidator;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * LISTAR USUÁRIOS
     * Admin → todos
     * Simples → apenas ele mesmo
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        return response()->json(
            User::with('pessoaFisica', 'pessoaJuridica')->get()
        );
    }

    /**
     * CRIAR USUÁRIO
     * Apenas administrador pode criar
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $base = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'tipo' => 'required|in:pf,pj',
            'nivel' => 'required|in:admin,simples',
            'status' => 'required|in:ativo,inativo',

            'cep' => 'required|string|size:8',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request, $base) {

            $cep = preg_replace('/\D/', '', $base['cep']);

            try {
                $response = Http::retry(3, 300)->timeout(5)
                    ->get("https://viacep.com.br/ws/{$cep}/json/");
            } catch (\Throwable $e) {
                $response = null;
            }

            $cepData = null;

            // Se deu exception (ou falhou total)
            if ($response === null) {
                $cepData = null;
            } elseif ($response->serverError()) {
                $cepData = null;
            } elseif ($response->clientError()) {
                return response()->json([
                    'message' => 'Falha ao consultar o ViaCEP.',
                ], 502);
            } else {
                $data = $response->json();

                if (isset($data['erro']) && $data['erro'] === true) {
                    return response()->json([
                        'message' => 'CEP não encontrado.',
                    ], 422);
                }

                $cepData = $data;
            }

            $user = User::create([
                ...$base,
                'password' => Hash::make($base['password']),
                'cep' => $cep,
                'logradouro' => $cepData['logradouro'] ?? null,
                'bairro' => $cepData['bairro'] ?? null,
                'cidade' => $cepData['localidade'] ?? null,
                'estado' => $cepData['uf'] ?? null,
            ]);

            if ($user->tipo === 'pf') {

                $pf = $request->validate([
                    'nome' => 'required|string|max:255',
                    'cpf' => 'required|string|unique:pessoas_fisicas,cpf',
                    'nascimento' => 'nullable|date',
                ]);

                if (isset($pfData['cpf'])) {
                    $cpf = preg_replace('/\D/', '', $pfData['cpf']);
                    if (!DocValidator::cpf($cpf)) {
                        return response()->json(['message' => 'CPF inválido.'], 422);
                    }
                    $pfData['cpf'] = $cpf;
                }

                PessoaFisica::create([
                    'user_id' => $user->id,
                    ...$pf
                ]);
            }

            if ($user->tipo === 'pj') {

                $pj = $request->validate([
                    'razao_social' => 'required|string|max:255',
                    'nome_fantasia' => 'required|string|max:255',
                    'cnpj' => 'required|string|unique:pessoas_juridicas,cnpj',
                ]);

                if (isset($pjData['cnpj'])) {
                    $cnpj = preg_replace('/\D/', '', $pjData['cnpj']);
                    if (!DocValidator::cnpj($cnpj)) {
                        return response()->json(['message' => 'CNPJ inválido.'], 422);
                    }

                    $receita = app(ReceitaWsService::class)->consultar($cnpj);

                    if (!$receita['ok']) {
                        if (($receita['error'] ?? '') === 'indisponivel') {
                            return response()->json(['message' => 'ReceitaWS indisponível. Tente novamente.'], 503);
                        }
                        return response()->json(['message' => $receita['message'] ?? 'CNPJ não encontrado na ReceitaWS.'], 422);
                    }

                    $pjData['cnpj'] = $cnpj;
                }

                PessoaJuridica::create([
                    'user_id' => $user->id,
                    ...$pj
                ]);
            }

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'user' => $user->load('pessoaFisica', 'pessoaJuridica')
            ], 201);
        });
    }

    /**
     * VISUALIZAR UM USUÁRIO
     * A Policy define quem pode acessar
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->json(
            $user->load('pessoaFisica', 'pessoaJuridica')
        );
    }

    /**
     * ATUALIZAR USUÁRIO
     * Simples → apenas próprio
     * Admin → qualquer usuário
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $auth = $request->user();

        return DB::transaction(function () use ($request, $user, $auth) {

            $userData = $request->validate([
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:6',

                'cep' => 'sometimes|string|size:8',
                'numero' => 'sometimes|nullable|string|max:20',
                'complemento' => 'sometimes|nullable|string|max:255',
            ]);

            unset($userData['tipo']);

            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            // Se vier CEP, consulta ViaCEP
            if (isset($userData['cep'])) {
                $cep = preg_replace('/\D/', '', $userData['cep']);

                try {
                    $response = Http::retry(3, 300)->timeout(5)
                        ->get("https://viacep.com.br/ws/{$cep}/json/");
                } catch (\Throwable $e) {
                    $response = null;
                }

                if ($response === null || $response->serverError()) {
                    $userData['cep'] = $cep;
                    $userData['logradouro'] = null;
                    $userData['bairro'] = null;
                    $userData['cidade'] = null;
                    $userData['estado'] = null;
                } elseif ($response->clientError()) {
                    return response()->json([
                        'message' => 'Falha ao consultar o ViaCEP.',
                    ], 502);
                } else {
                    $cepData = $response->json();

                    if (isset($cepData['erro']) && $cepData['erro'] === true) {
                        return response()->json([
                            'message' => 'CEP não encontrado.',
                        ], 422);
                    }

                    $userData['cep'] = $cep;
                    $userData['logradouro'] = $cepData['logradouro'] ?? null;
                    $userData['bairro'] = $cepData['bairro'] ?? null;
                    $userData['cidade'] = $cepData['localidade'] ?? null;
                    $userData['estado'] = $cepData['uf'] ?? null;
                }
            }

            if ($auth->nivel === 'admin') {
                $extra = $request->validate([
                    'nivel' => 'sometimes|in:admin,simples',
                    'status' => 'sometimes|in:ativo,inativo',
                ]);

                $userData = array_merge($userData, $extra);
            }

            $user->update($userData);

            if ($auth->nivel === 'admin') {

                if ($user->tipo === 'pf' && $user->pessoaFisica) {

                    $pfData = $request->validate([
                        'nome' => 'sometimes|string|max:255',
                        'cpf' => 'sometimes|string|unique:pessoas_fisicas,cpf,' . $user->pessoaFisica->id,
                        'nascimento' => 'sometimes|date',
                    ]);

                    $cpf = preg_replace('/\D/', '', $pfData['cpf']);

                    if (!DocValidator::cpf($cpf)) {
                        return response()->json(['message' => 'CPF inválido.'], 422);
                    }

                    $pf['cpf'] = $cpf;


                    if (!empty($pfData)) {
                        $user->pessoaFisica->update($pfData);
                    }
                }

                if ($user->tipo === 'pj' && $user->pessoaJuridica) {

                    $pjData = $request->validate([
                        'razao_social' => 'sometimes|string|max:255',
                        'nome_fantasia' => 'sometimes|string|max:255',
                        'cnpj' => 'sometimes|string|unique:pessoas_juridicas,cnpj,' . $user->pessoaJuridica->id,
                    ]);

                    $cnpj = preg_replace('/\D/', '', $pjData['cnpj']);

                    if (!DocValidator::cnpj($cnpj)) {
                        return response()->json(['message' => 'CNPJ inválido.'], 422);
                    }

                    // Consulta ReceitaWS
                    $receita = app(ReceitaWsService::class)->consultar($cnpj);

                    if (!$receita['ok']) {
                        // aqui você escolhe a regra:
                        // se a Receita cair, eu recomendo retornar 503 (mais “validação real”)
                        if (($receita['error'] ?? '') === 'indisponivel') {
                            return response()->json(['message' => 'ReceitaWS indisponível. Tente novamente.'], 503);
                        }

                        return response()->json(['message' => $receita['message'] ?? 'CNPJ não encontrado na ReceitaWS.'], 422);
                    }

                    $pj['cnpj'] = $cnpj;

                    if (!empty($pjData)) {
                        $user->pessoaJuridica->update($pjData);
                    }
                }
            }

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'user' => $user->load('pessoaFisica', 'pessoaJuridica')
            ]);
        });
    }

    /**
     * DELETAR USUÁRIO
     * Utiliza Soft Delete
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'Usuário deletado'
        ]);
    }
}
