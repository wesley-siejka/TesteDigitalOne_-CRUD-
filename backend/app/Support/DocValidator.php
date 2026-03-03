<?php

namespace App\Support;

class DocValidator
{
    public static function cpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11) return false;
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) $sum += (int)$cpf[$i] * (($t + 1) - $i);
            $d = ((10 * $sum) % 11) % 10;
            if ((int)$cpf[$t] !== $d) return false;
        }
        return true;
    }

    public static function cnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);
        if (strlen($cnpj) !== 14) return false;
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) return false;

        $w1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $w2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];

        $calc = function($base, $weights) {
            $sum = 0;
            foreach ($weights as $i => $w) $sum += (int)$base[$i] * $w;
            $mod = $sum % 11;
            return ($mod < 2) ? 0 : 11 - $mod;
        };

        $d1 = $calc(substr($cnpj, 0, 12), $w1);
        $d2 = $calc(substr($cnpj, 0, 13), $w2);

        return ((int)$cnpj[12] === $d1) && ((int)$cnpj[13] === $d2);
    }
}
