<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReceitaWsService
{
    public function consultar(string $cnpj): array
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        $res = Http::retry(3, 300)->timeout(8)->acceptJson()
            ->get("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

        if ($res->serverError()) {
            return ['ok' => false, 'error' => 'indisponivel', 'status' => $res->status()];
        }

        $data = $res->json();

        if (($data['status'] ?? null) === 'ERROR') {
            return ['ok' => false, 'error' => 'cnpj_invalido', 'message' => $data['message'] ?? 'CNPJ inválido'];
        }

        return ['ok' => true, 'data' => $data];
    }
}
