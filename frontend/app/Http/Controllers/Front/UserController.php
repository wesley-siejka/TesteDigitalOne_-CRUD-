<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(ApiClient $api)
    {
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

        $res = $api->authed()->post('/api/users', $data);

        if ($res->failed()) {
            return back()->withErrors(['erro' => 'Erro ao criar usuário'])->withInput();
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
}
