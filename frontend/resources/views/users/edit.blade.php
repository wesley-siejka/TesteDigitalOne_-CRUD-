@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')

    @php
        $isAdmin = data_get(session('user'), 'nivel') === 'admin';
        $nivelAtual = $user['nivel'] ?? '';
        $statusAtual = $user['status'] ?? '';
    @endphp

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Editar Usuário #{{ $user['id'] }}</h3>

        <div class="d-flex gap-2">
            @if($isAdmin)
                <form method="POST" action="/users/{{ $user['id'] }}/reset-password">
                    @csrf

                    <div class="input-group">
                        <input type="password" name="admin_password" class="form-control" placeholder="Senha do admin"
                            required>

                        <button class="btn btn-warning" onclick="return confirm('Resetar senha do usuário para 123456?')">
                            Resetar Senha
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Confira os campos:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/users/{{ $user['id'] }}">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-header">Dados do usuário</div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $user['email'] ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo</label>
                        <input class="form-control" value="{{ $user['tipo'] ?? '' }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nível</label>

                        <select class="form-select" name="nivel" @disabled(!$isAdmin)>
                            <option value="simples" @selected(old('nivel', $nivelAtual) === 'simples')>Simples</option>
                            <option value="admin" @selected(old('nivel', $nivelAtual) === 'admin')>Admin</option>
                        </select>

                        @if (!$isAdmin)
                            <div class="form-text">Apenas admin pode alterar.</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>

                        <select class="form-select" name="status" @disabled(!$isAdmin)>
                            <option value="ativo" @selected(old('status', $statusAtual) === 'ativo')>Ativo</option>
                            <option value="inativo" @selected(old('status', $statusAtual) === 'inativo')>Inativo</option>
                        </select>

                        @if (!$isAdmin)
                            <div class="form-text">Apenas admin pode alterar.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- PF --}}
        <div class="card mb-3" id="bloco-pf">
            <div class="card-header">Dados Pessoa Física (admin)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>
                        <input class="form-control" name="nome"
                            value="{{ old('nome', $user['pessoa_fisica']['nome'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF</label>
                        <input class="form-control" name="cpf"
                            value="{{ old('cpf', $user['pessoa_fisica']['cpf'] ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nascimento</label>
                        <input type="date" class="form-control" name="nascimento"
                            value="{{ old('nascimento', $user['pessoa_fisica']['nascimento'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- PJ --}}
        <div class="card mb-3" id="bloco-pj">
            <div class="card-header">Dados Pessoa Jurídica (admin)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Razão Social</label>
                        <input class="form-control" name="razao_social"
                            value="{{ old('razao_social', $user['pessoa_juridica']['razao_social'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome Fantasia</label>
                        <input class="form-control" name="nome_fantasia"
                            value="{{ old('nome_fantasia', $user['pessoa_juridica']['nome_fantasia'] ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">CNPJ</label>
                        <input class="form-control" name="cnpj"
                            value="{{ old('cnpj', $user['pessoa_juridica']['cnpj'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Endereço --}}
        <div class="card mb-3">
            <div class="card-header">Endereço</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">CEP</label>
                        <input class="form-control" name="cep" value="{{ old('cep', $user['cep'] ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número</label>
                        <input class="form-control" name="numero" value="{{ old('numero', $user['numero'] ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Complemento</label>
                        <input class="form-control" name="complemento"
                            value="{{ old('complemento', $user['complemento'] ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logradouro</label>
                        <input class="form-control" value="{{ $user['logradouro'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bairro</label>
                        <input class="form-control" value="{{ $user['bairro'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cidade</label>
                        <input class="form-control" value="{{ $user['cidade'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Estado</label>
                        <input class="form-control" value="{{ $user['estado'] ?? '' }}" readonly>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Salvar alterações</button>
            <a href="/users" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <script>
        (function() {
            const tipo = @json($user['tipo'] ?? 'pf');
            const pf = document.getElementById('bloco-pf');
            const pj = document.getElementById('bloco-pj');

            pf.style.display = (tipo === 'pf') ? '' : 'none';
            pj.style.display = (tipo === 'pj') ? '' : 'none';
        })();
    </script>
@endsection
