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

        return redirect('/users');
    }

    public function logout()
    {
        session()->forget('api_token');
        return redirect()->route('login');
    }
}