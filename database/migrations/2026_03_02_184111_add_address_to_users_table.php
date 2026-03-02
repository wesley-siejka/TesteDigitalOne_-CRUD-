<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cep', 8)->nullable()->after('email');
            $table->string('logradouro')->nullable()->after('cep');
            $table->string('bairro')->nullable()->after('logradouro');
            $table->string('numero')->nullable()->after('bairro');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('estado', 2)->nullable()->after('complemento');
            $table->string('cidade')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cep',
                'logradouro',
                'bairro',
                'numero',
                'complemento',
                'estado',
                'cidade',
            ]);
        });
    }
};
