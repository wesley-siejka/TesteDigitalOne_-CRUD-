<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PessoaFisica;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::create([
                'email' => 'admin@digitalone.com',
                'password' => '123456',
                'tipo' => 'pf',
                'nivel' => 'admin',
                'status' => 'ativo',
            ]);

            PessoaFisica::create([
                'user_id' => $user->id,
                'nome' => 'Administrador',
                'cpf' => '29064963088',
                'nascimento' => now(),
            ]);

        });
    }
}
