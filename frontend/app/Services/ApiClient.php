<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiClient
{
    public function base(): PendingRequest
    {
        return Http::baseUrl(config('services.api.url'))
            ->acceptJson();
    }

    public function authed(): PendingRequest
    {
        $token = session('api_token');

        return $this->base()
            ->when($token, fn ($req) => $req->withToken($token));
    }
}