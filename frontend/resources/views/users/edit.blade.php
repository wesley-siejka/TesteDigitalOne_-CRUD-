@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')

    @php
        $authId = data_get(session('user'), 'id');
        $authNivel = data_get(session('user'), 'nivel');
        $isAdmin = $authNivel === 'admin';
        $isSelf = $authId == ($user['id'] ?? null);

        // Pode alterar nivel/status somente se for admin E não estiver editando a si mesmo
        $canEditLevelStatus = $isAdmin && !$isSelf;
    @endphp

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Editar Usuário #{{ $user['id'] }}</h3>

        <div class="d-flex gap-2">
            <a href="/users/{{ $user['id'] }}/password" class="btn btn-warning">
                Redefinir senha
            </a>

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

                        <select class="form-select" name="nivel" @disabled(!$canEditLevelStatus)>
                            <option value="simples" @selected(old('nivel', $user['nivel'] ?? '') === 'simples')>Simples</option>
                            <option value="admin" @selected(old('nivel', $user['nivel'] ?? '') === 'admin')>Admin</option>
                        </select>
                        @if (!$canEditLevelStatus)
                            <div class="form-text">Você não pode alterar nível neste caso.</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>

                        <select class="form-select" name="status" @disabled(!$canEditLevelStatus)>
                            <option value="ativo" @selected(old('status', $user['status'] ?? '') === 'ativo')>Ativo</option>
                            <option value="inativo" @selected(old('status', $user['status'] ?? '') === 'inativo')>Inativo</option>
                        </select>
                        @if (!$canEditLevelStatus)
                            <div class="form-text">Você não pode alterar status neste caso.</div>
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
                        <input class="form-control" name="cpf" inputmode="numeric" maxlength="14"
                            placeholder="000.000.000-00" value="{{ old('cpf', $user['pessoa_fisica']['cpf'] ?? '') }}">
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
                        <input class="form-control" name="cnpj" inputmode="numeric" maxlength="18"
                            placeholder="00.000.000/0000-00"
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
                        <input class="form-control" name="cep" inputmode="numeric" maxlength="9"
                            placeholder="00000-000" value="{{ old('cep', $user['cep'] ?? '') }}">
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

        const onlyDigits = (v) => (v || '').replace(/\D/g, '');

        function maskCEP(v) {
            v = onlyDigits(v).slice(0, 8);
            return v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;
        }

        function maskCPF(v) {
            v = onlyDigits(v).slice(0, 11);
            if (v.length <= 3) return v;
            if (v.length <= 6) return v.slice(0, 3) + '.' + v.slice(3);
            if (v.length <= 9) return v.slice(0, 3) + '.' + v.slice(3, 6) + '.' + v.slice(6);
            return v.slice(0, 3) + '.' + v.slice(3, 6) + '.' + v.slice(6, 9) + '-' + v.slice(9);
        }

        function maskCNPJ(v) {
            v = onlyDigits(v).slice(0, 14);
            if (v.length <= 2) return v;
            if (v.length <= 5) return v.slice(0, 2) + '.' + v.slice(2);
            if (v.length <= 8) return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5);
            if (v.length <= 12) return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5, 8) + '/' + v.slice(8);
            return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5, 8) + '/' + v.slice(8, 12) + '-' + v.slice(12);
        }

        const cepInput = document.querySelector('input[name="cep"]');
        const cpfInput = document.querySelector('input[name="cpf"]');
        const cnpjInput = document.querySelector('input[name="cnpj"]');

        if (cepInput && cepInput.value) cepInput.value = maskCEP(cepInput.value);
        if (cpfInput && cpfInput.value) cpfInput.value = maskCPF(cpfInput.value);
        if (cnpjInput && cnpjInput.value) cnpjInput.value = maskCNPJ(cnpjInput.value);

        if (cepInput) {
            cepInput.addEventListener('input', () => {
                cepInput.value = maskCEP(cepInput.value);
            });
        }

        if (cpfInput) {
            cpfInput.addEventListener('input', () => {
                cpfInput.value = maskCPF(cpfInput.value);
            });
        }

        if (cnpjInput) {
            cnpjInput.addEventListener('input', () => {
                cnpjInput.value = maskCNPJ(cnpjInput.value);
            });
        }
    </script>
@endsection
