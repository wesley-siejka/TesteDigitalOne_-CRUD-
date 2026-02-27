<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\PessoaFisica;
use App\Models\PessoaJuridica;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

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
        $authUser = $request->user();

        if ($authUser->nivel === 'admin') {
            // Carrega também dados PF/PJ
            return response()->json(
                User::with('pessoaFisica', 'pessoaJuridica')->get()
            );
        }

        // Usuário simples só vê a si mesmo
        return response()->json([
            $authUser->load('pessoaFisica', 'pessoaJuridica')
        ]);
    }

    /**
     * CRIAR USUÁRIO (Admin apenas)
     * Cria também PF ou PJ conforme o tipo
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        // Validação base (users)
        $base = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'tipo' => 'required|in:pf,pj',
            'nivel' => 'required|in:admin,simples',
            'status' => 'required|in:ativo,inativo',
        ]);

        return DB::transaction(function () use ($request, $base) {

            // Cria o usuário
            $user = User::create($base);

            // Se for PF → cria registro em pessoas_fisicas
            if ($user->tipo === 'pf') {

                $pf = $request->validate([
                    'nome' => 'required|string|max:255',
                    'cpf' => 'required|string|unique:pessoas_fisicas,cpf',
                    'nascimento' => 'nullable|date',
                ]);

                PessoaFisica::create([
                    'user_id' => $user->id,
                    ...$pf
                ]);
            }

            // Se for PJ → cria registro em pessoas_juridicas
            if ($user->tipo === 'pj') {

                $pj = $request->validate([
                    'razao_social' => 'required|string|max:255',
                    'nome_fantasia' => 'required|string|max:255',
                    'cnpj' => 'required|string|unique:pessoas_juridicas,cnpj',
                ]);

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
     * Policy já garante quem pode ver
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
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $auth = $request->user();

        return DB::transaction(function () use ($request, $user, $auth) {

            // TODOS podem alterar email e senha
            $userData = $request->validate([
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:6',
            ]);

            // Tipo nunca pode ser alterado
            unset($userData['tipo']);

            // Apenas ADMIN pode alterar nível e status
            if ($auth->nivel === 'admin') {

                $extra = $request->validate([
                    'nivel' => 'sometimes|in:admin,simples',
                    'status' => 'sometimes|in:ativo,inativo',
                ]);

                $userData = array_merge($userData, $extra);
            }

            $user->update($userData);

            // ADMIN pode alterar dados PF/PJ
            if ($auth->nivel === 'admin') {

                if ($user->tipo === 'pf' && $user->pessoaFisica) {

                    $pfData = $request->validate([
                        'nome' => 'sometimes|string|max:255',
                        'cpf' => 'sometimes|string|unique:pessoas_fisicas,cpf,' . $user->pessoaFisica->id,
                        'nascimento' => 'sometimes|date',
                    ]);

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
     * DELETAR USUÁRIO (Soft Delete)
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
