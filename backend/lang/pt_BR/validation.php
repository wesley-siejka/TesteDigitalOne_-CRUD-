<?php

return [

    'required' => 'O campo :attribute é obrigatório.',
    'email' => 'O campo :attribute deve ser um e-mail válido.',
    'min' => [
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'max' => [
        'string' => 'O campo :attribute deve ter no máximo :max caracteres.',
    ],
    'unique' => 'O :attribute já está em uso.',
    'in' => 'O valor selecionado para :attribute é inválido.',
    'date' => 'O campo :attribute deve ser uma data válida.',
    'size' => [
        'string' => 'O campo :attribute deve ter :size caracteres.',
    ],

    'attributes' => [
        'email' => 'e-mail',
        'password' => 'senha',
        'nome' => 'nome',
        'cpf' => 'CPF',
        'cnpj' => 'CNPJ',
        'razao_social' => 'razão social',
        'nome_fantasia' => 'nome fantasia',
        'cep' => 'CEP',
        'nivel' => 'nível',
        'status' => 'status',
    ],

];
