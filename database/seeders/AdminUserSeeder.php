<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'email' => 'admin@digitalone.com',
            'password' => Hash::make('Admin123'),
            'tipo' => 'pf',
            'nivel' => 'admin',
            'status' => 'ativo'
        ]);
    }
}
