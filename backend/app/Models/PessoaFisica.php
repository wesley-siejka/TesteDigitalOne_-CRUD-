<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PessoaFisica extends Model
{
    protected $table = 'pessoas_fisicas';
    
    protected $fillable = [
        'user_id',
        'nome',
        'nascimento',
        'cpf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
