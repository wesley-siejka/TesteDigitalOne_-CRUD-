@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Novo Usuário</h3>
    <a href="/users" class="btn btn-outline-secondary">Voltar</a>
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

<form method="POST" action="/users">
    @csrf

    {{-- DADOS BASE --}}
    <div class="card mb-3">
        <div class="card-header">Dados do usuário</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" id="tipo">
                        <option value="pf" @selected(old('tipo','pf') === 'pf')>Pessoa Física</option>
                        <option value="pj" @selected(old('tipo') === 'pj')>Pessoa Jurídica</option>
                    </select>
                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nível</label>
                    <select class="form-select @error('nivel') is-invalid @enderror" name="nivel">
                        <option value="simples" @selected(old('nivel','simples')==='simples')>Simples</option>
                        <option value="admin" @selected(old('nivel')==='admin')>Admin</option>
                    </select>
                    @error('nivel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                        <option value="ativo" @selected(old('status','ativo')==='ativo')>Ativo</option>
                        <option value="inativo" @selected(old('status')==='inativo')>Inativo</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- PF --}}
    <div class="card mb-3" id="bloco-pf">
        <div class="card-header">Dados Pessoa Física</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome</label>
                    <input class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}">
                    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">CPF</label>
                    <input class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}">
                    @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nascimento</label>
                    <input type="date" class="form-control @error('nascimento') is-invalid @enderror" name="nascimento" value="{{ old('nascimento') }}">
                    @error('nascimento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- PJ --}}
    <div class="card mb-3" id="bloco-pj">
        <div class="card-header">Dados Pessoa Jurídica</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Razão Social</label>
                    <input class="form-control @error('razao_social') is-invalid @enderror" name="razao_social" value="{{ old('razao_social') }}">
                    @error('razao_social') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome Fantasia</label>
                    <input class="form-control @error('nome_fantasia') is-invalid @enderror" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
                    @error('nome_fantasia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">CNPJ</label>
                    <input class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{ old('cnpj') }}">
                    @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ENDEREÇO --}}
    <div class="card mb-3">
        <div class="card-header">Endereço</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">CEP</label>
                    <input class="form-control @error('cep') is-invalid @enderror" name="cep" value="{{ old('cep') }}" placeholder="Somente números">
                    @error('cep') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Número</label>
                    <input class="form-control @error('numero') is-invalid @enderror" name="numero" value="{{ old('numero') }}">
                    @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Complemento</label>
                    <input class="form-control @error('complemento') is-invalid @enderror" name="complemento" value="{{ old('complemento') }}">
                    @error('complemento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="alert alert-secondary mb-0">
                Logradouro, bairro, cidade e estado serão preenchidos automaticamente pela API via CEP.
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success">Salvar</button>
        <a href="/users" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<script>
(function () {
    const tipo = document.getElementById('tipo');
    const pf = document.getElementById('bloco-pf');
    const pj = document.getElementById('bloco-pj');

    function toggle() {
        const v = tipo.value;
        pf.style.display = (v === 'pf') ? '' : 'none';
        pj.style.display = (v === 'pj') ? '' : 'none';
    }

    tipo.addEventListener('change', toggle);
    toggle();
})();
</script>
@endsection