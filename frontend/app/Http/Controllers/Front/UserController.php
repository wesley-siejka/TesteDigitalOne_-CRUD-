<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(ApiClient $api)
    {
        // BLOQUEIA USUÁRIO SIMPLES
        if (data_get(session('user'), 'nivel') !== 'admin') {
            return redirect('/me');
        }

        $res = $api->authed()->get('/api/users');

        if ($res->failed()) {
            abort(500, 'Erro ao buscar usuários na API');
        }

        $users = $res->json();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request, ApiClient $api)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'tipo' => ['required', 'in:pf,pj'],
            'nivel' => ['required', 'in:admin,simples'],
            'status' => ['required', 'in:ativo,inativo'],
            'cep' => ['required'],

            'nome' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string'],
            'nascimento' => ['nullable', 'date'],

            'razao_social' => ['nullable', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string'],
        ]);

        // remove máscaras antes de enviar pra API
        $data['cep'] = preg_replace('/\D/', '', $data['cep'] ?? '');
        $data['cpf'] = preg_replace('/\D/', '', $data['cpf'] ?? '');
        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj'] ?? '');

        // validações rápidas (sem API externa)
        if (strlen($data['cep']) !== 8) {
            return back()->withErrors(['cep' => 'CEP deve ter 8 dígitos'])->withInput();
        }

        if ($data['tipo'] === 'pf') {
            if (empty($data['nome'])) {
                return back()->withErrors(['nome' => 'Nome é obrigatório para PF'])->withInput();
            }
            if (strlen($data['cpf']) !== 11) {
                return back()->withErrors(['cpf' => 'CPF deve ter 11 dígitos'])->withInput();
            }
            if (!$this->isValidCpf($data['cpf'])) {
                return back()->withErrors(['cpf' => 'CPF inválido'])->withInput();
            }
        }

        if ($data['tipo'] === 'pj') {
            if (empty($data['razao_social'])) {
                return back()->withErrors(['razao_social' => 'Razão Social é obrigatória para PJ'])->withInput();
            }
            if (empty($data['nome_fantasia'])) {
                return back()->withErrors(['nome_fantasia' => 'Nome Fantasia é obrigatório para PJ'])->withInput();
            }
            if (strlen($data['cnpj']) !== 14) {
                return back()->withErrors(['cnpj' => 'CNPJ deve ter 14 dígitos'])->withInput();
            }
            if (!$this->isValidCnpj($data['cnpj'])) {
                return back()->withErrors(['cnpj' => 'CNPJ inválido'])->withInput();
            }
        }

        $res = $api->authed()->post('/api/users', $data);

        if ($res->status() === 422) {
            return back()->withErrors($res->json('errors') ?? ['erro' => $res->json('message') ?? 'Erro de validação'])->withInput();
        }

        if ($res->failed()) {
            return back()->withErrors(['erro' => 'API ' . $res->status() . ' - ' . ($res->json('message') ?? 'Erro ao criar usuário')])->withInput();
        }

        return redirect('/users');
    }

    public function cep(string $cep, ApiClient $api)
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return response()->json(['message' => 'CEP inválido'], 422);
        }

        $res = $api->authed()->get("/api/cep/{$cep}");

        if ($res->failed()) {
            return response()->json([
                'message' => $res->json('message') ?? 'Falha ao consultar CEP',
            ], $res->status());
        }

        return response()->json($res->json());
    }

    private function isValidCpf(string $cpf): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += (int)$cpf[$i] * (($t + 1) - $i);
            }
            $d = ((10 * $sum) % 11) % 10;
            if ((int)$cpf[$t] !== $d) return false;
        }
        return true;
    }

    private function isValidCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) return false;

        $w1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $w2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $calc = function ($base, $weights) {
            $sum = 0;
            foreach ($weights as $i => $w) $sum += (int)$base[$i] * $w;
            $mod = $sum % 11;
            return ($mod < 2) ? 0 : 11 - $mod;
        };

        $d1 = $calc(substr($cnpj, 0, 12), $w1);
        $d2 = $calc(substr($cnpj, 0, 13), $w2);

        return ((int)$cnpj[12] === $d1) && ((int)$cnpj[13] === $d2);
    }

    public function edit(int $id, ApiClient $api)
    {
        $res = $api->authed()->get("/api/users/{$id}");

        if ($res->status() === 403) {
            abort(403, 'Sem permissão.');
        }

        if ($res->failed()) {
            abort(500, 'Erro ao carregar usuário.');
        }

        $user = $res->json();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, int $id, ApiClient $api)
    {
        $data = $request->validate([
            'email' => ['sometimes', 'email'],
            'password' => ['nullable'],

            'cep' => ['sometimes'],
            'numero' => ['nullable'],
            'complemento' => ['nullable'],

            // PF (admin)
            'nome' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string'],
            'nascimento' => ['nullable', 'date'],

            // PJ (admin)
            'razao_social' => ['nullable', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string'],

            // admin
            'nivel' => ['nullable', 'in:admin,simples'],
            'status' => ['nullable', 'in:ativo,inativo'],
        ]);

        $authId = data_get(session('user'), 'id');
        $isAdmin = data_get(session('user'), 'nivel') === 'admin';
        $isSelf = ($authId == $id);

        // Só admin e não-self pode alterar nivel/status
        if (!$isAdmin || $isSelf) {
            unset($data['nivel'], $data['status']);
        }

        // se não for admin, não deixa enviar nivel/status (mesmo se alguém tentar pelo devtools)
        if (data_get(session('user'), 'nivel') !== 'admin') {
            unset($data['nivel'], $data['status']);
        }

        // remove máscaras
        if (isset($data['cep']))  $data['cep']  = preg_replace('/\D/', '', $data['cep']);
        if (isset($data['cpf']))  $data['cpf']  = preg_replace('/\D/', '', $data['cpf']);
        if (isset($data['cnpj'])) $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        // se senha vazia, não envia
        if (array_key_exists('password', $data) && !$data['password']) {
            unset($data['password']);
        }

        $res = $api->authed()->put("/api/users/{$id}", $data);

        if ($res->status() === 422) {
            return back()->withErrors($res->json('errors') ?? ['erro' => $res->json('message') ?? 'Erro de validação'])->withInput();
        }

        if ($res->status() === 403) {
            return back()->withErrors(['erro' => 'Sem permissão para atualizar este usuário.'])->withInput();
        }

        if ($res->failed()) {
            return back()->withErrors(['erro' => 'API ' . $res->status() . ' - ' . ($res->json('message') ?? 'Erro ao atualizar')])->withInput();
        }

        return redirect('/users');
    }

    public function resetPassword(int $id, Request $request, ApiClient $api)
    {
        $request->validate([
            'admin_password' => ['required']
        ]);

        $res = $api->authed()->post("/api/users/{$id}/reset-password", [
            'admin_password' => $request->admin_password
        ]);

        if ($res->failed()) {
            return back()->withErrors([
                'erro' => $res->json('message') ?? 'Erro ao resetar senha'
            ]);
        }

        return back()->with('success', 'Senha resetada para 123456');
    }

    public function passwordForm(int $id, ApiClient $api)
    {
        $authId = data_get(session('user'), 'id');
        $isAdmin = data_get(session('user'), 'nivel') === 'admin';
        $isSelf = ($authId == $id);

        // se não for admin e tentar mexer em outro usuário → bloqueia
        if (!$isAdmin && !$isSelf) {
            abort(403, 'Sem permissão.');
        }

        // pega o usuário só pra mostrar nome/id na tela
        $u = $api->authed()->get("/api/users/{$id}");
        if ($u->failed()) abort(500, 'Erro ao carregar usuário.');

        // admin resetando OUTRO: modo admin
        // qualquer um resetando a PRÓPRIA: modo self (inclusive admin)
        $mode = ($isAdmin && !$isSelf) ? 'admin' : 'self';

        return view('users.password', [
            'user' => $u->json(),
            'mode' => $mode,
        ]);
    }

    public function passwordUpdate(int $id, Request $request, ApiClient $api)
    {
        // dd($request->all());

        $authId = data_get(session('user'), 'id');
        $isAdmin = data_get(session('user'), 'nivel') === 'admin';
        $isSelf = ($authId == $id);

        if (!$isAdmin && !$isSelf) {
            abort(403, 'Sem permissão.');
        }

        // admin resetando senha de OUTRO usuário
        if ($isAdmin && !$isSelf) {
            $data = $request->validate([
                'admin_password' => ['required'],
            ]);

            $res = $api->authed()->post("/api/users/{$id}/reset-password", $data);

            if ($res->status() === 422) {
                return back()->withErrors($res->json('errors') ?? ['erro' => $res->json('message')])->withInput();
            }
            if ($res->failed()) {
                return back()->withErrors(['erro' => $res->json('message') ?? 'Erro ao resetar senha'])->withInput();
            }

            return redirect("/users/{$id}/edit")->with('success', 'Senha resetada para 123456');
        }

        // qualquer um trocando a PRÓPRIA senha (inclusive admin)
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        // endpoint do próprio user
        $res = $api->authed()->put("/api/me/password", $data);

        if ($res->status() === 422) {
            return back()->withErrors($res->json('errors') ?? ['erro' => $res->json('message')])->withInput();
        }
        if ($res->failed()) {
            return back()->withErrors(['erro' => $res->json('message') ?? 'Erro ao alterar senha'])->withInput();
        }

        return redirect("/users/{$id}/edit")->with('success', 'Senha alterada com sucesso');
    }

    public function me(ApiClient $api)
    {
        // pega o usuário logado
        $me = $api->authed()->get('/api/user');
        if ($me->failed()) abort(500, 'Erro ao carregar seu usuário.');

        $id = data_get($me->json(), 'id');

        // agora pega completo (com pessoaFisica/pessoaJuridica)
        $res = $api->authed()->get("/api/users/{$id}");
        if ($res->failed()) abort(500, 'Erro ao carregar seu usuário.');

        $user = $res->json();
        return view('users.show', compact('user'));
    }

    public function destroy(int $id, ApiClient $api)
    {

        $authId = data_get(session('user'), 'id');

        if ($authId == $id) {
            return back()->withErrors(['erro' => 'Você não pode excluir sua própria conta.']);
        }

        $res = $api->authed()->delete("/api/users/{$id}");

        if ($res->failed()) {
            return back()->withErrors([
                'erro' => $res->json('message') ?? 'Erro ao excluir usuário'
            ]);
        }

        return redirect('/users')->with('success', 'Usuário excluído com sucesso');
    }
}
