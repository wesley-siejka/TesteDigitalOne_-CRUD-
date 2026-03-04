<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request, ApiClient $api)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $res = $api->base()->post('/api/login', $data);

        if ($res->failed()) {
            return back()
                ->withErrors(['login' => 'Email ou senha inválidos'])
                ->withInput();
        }

        session(['api_token' => $res->json('token')]);

        $token = $res->json('token'); // ou o campo certo
        session(['api_token' => $token]);

        $me = $api->authed()->get('/api/user');
        if ($me->ok()) {
            session(['user' => $me->json()]);
        }

        return data_get(session('user'), 'nivel') === 'admin'
            ? redirect('/users')
            : redirect('/me');
    }

    public function logout()
    {
        session()->forget(['token', 'user']);
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }
}
