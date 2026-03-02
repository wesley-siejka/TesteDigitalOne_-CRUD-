<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrontAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('api_token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}