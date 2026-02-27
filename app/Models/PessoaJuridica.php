<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PessoaJuridica extends Model
{
    protected $table = 'pessoas_juridicas';
    
    protected $fillable = [
        'user_id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
