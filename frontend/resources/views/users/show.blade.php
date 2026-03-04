@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Meu Perfil</h3>

        <div class="d-flex gap-2">
            <a href="/users/{{ $user['id'] }}/edit" class="btn btn-outline-primary">Editar</a>
            <a href="/users/{{ $user['id'] }}/password" class="btn btn-warning">Redefinir senha</a>
        </div>
    </div>

    <div class="row g-3">
        {{-- CARD PRINCIPAL --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Dados da conta</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2"><strong>Email:</strong> {{ $user['email'] ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Tipo:</strong> {{ strtoupper($user['tipo'] ?? '-') }}</div>
                        <div class="col-md-4 mb-2"><strong>Nível:</strong> {{ $user['nivel'] ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Status:</strong> {{ $user['status'] ?? '-' }}</div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4 mb-2"><strong>CEP:</strong> {{ $user['cep'] ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Número:</strong> {{ $user['numero'] ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Complemento:</strong> {{ $user['complemento'] ?? '-' }}</div>

                        <div class="col-12 mb-2"><strong>Logradouro:</strong> {{ $user['logradouro'] ?? '-' }}</div>

                        <div class="col-md-4 mb-2"><strong>Bairro:</strong> {{ $user['bairro'] ?? '-' }}</div>
                        <div class="col-md-5 mb-2"><strong>Cidade:</strong> {{ $user['cidade'] ?? '-' }}</div>
                        <div class="col-md-3 mb-2"><strong>Estado:</strong> {{ $user['estado'] ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD DO TIPO (PF/PJ) --}}
        <div class="col-lg-6">
            @if (($user['tipo'] ?? '') === 'pf')
                <div class="card h-100">
                    <div class="card-header">Pessoa Física</div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Nome:</strong> {{ $user['pessoa_fisica']['nome'] ?? '-' }}</div>

                        @php($cpf = $user['pessoa_fisica']['cpf'] ?? '')
                        <div class="mb-2"><strong>CPF:</strong>
                            {{ $cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) : '-' }}
                        </div>

                        <div class="mb-2"><strong>Nascimento:</strong> {{ $user['pessoa_fisica']['nascimento'] ?? '-' }}
                        </div>
                    </div>
                </div>
            @elseif(($user['tipo'] ?? '') === 'pj')
                <div class="card h-100">
                    <div class="card-header">Pessoa Jurídica</div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Razão Social:</strong>
                            {{ $user['pessoa_juridica']['razao_social'] ?? '-' }}</div>
                        <div class="mb-2"><strong>Nome Fantasia:</strong>
                            {{ $user['pessoa_juridica']['nome_fantasia'] ?? '-' }}</div>

                        @php($cnpj = $user['pessoa_juridica']['cnpj'] ?? '')
                        <div class="mb-2"><strong>CNPJ:</strong>
                            {{ $cnpj ? preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) : '-' }}
                        </div>
                    </div>
                </div>
            @else
                <div class="card h-100">
                    <div class="card-header">Tipo</div>
                    <div class="card-body">
                        <div class="alert alert-secondary mb-0">Tipo do usuário não informado.</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
