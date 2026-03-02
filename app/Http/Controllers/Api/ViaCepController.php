<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ViaCepController extends Controller
{
    /**
     * Consulta endereço pelo CEP usando ViaCEP.
     */
    public function show(Request $request, string $cep)
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return response()->json([
                'message' => 'CEP inválido. Use 8 dígitos.',
            ], 422);
        }

        $cacheKey = "viacep:{$cep}";

        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($cep) {
            $response = Http::retry(3, 300)
                ->timeout(5)
                ->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->serverError()) {
                return ['_error' => 503];
            }

            if ($response->clientError()) {
                return ['_error' => 502];
            }

            return $response->json();
        });

        if (isset($data['_error']) && $data['_error'] === 503) {
            return response()->json([
                'message' => 'Serviço do ViaCEP indisponível no momento.',
            ], 503);
        }

        if (isset($data['_error']) && $data['_error'] === 502) {
            return response()->json([
                'message' => 'Falha ao consultar o ViaCEP.',
            ], 502);
        }

        if (isset($data['erro']) && $data['erro'] === true) {
            return response()->json([
                'message' => 'CEP não encontrado.',
            ], 422);
        }

        return response()->json([
            'cep' => $cep,
            'logradouro' => $data['logradouro'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'numero' => null,
            'complemento' => null,
            'cidade' => $data['localidade'] ?? null,
            'estado' => $data['uf'] ?? null,
        ]);
    }
}
