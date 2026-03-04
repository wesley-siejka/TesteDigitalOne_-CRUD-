@extends('layouts.app')

@section('title', 'Redefinir Senha')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Senha - Usuário #{{ $user['id'] }}</h3>
    <a href="/users/{{ $user['id'] }}/edit" class="btn btn-outline-secondary">Voltar</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="/users/{{ $user['id'] }}/password">
            @csrf

            @if($mode === 'admin')
                <div class="alert alert-warning">
                    Você está resetando a senha deste usuário para <strong>123456</strong>.
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirme sua senha (admin)</label>
                    <input type="password" name="admin_password" class="form-control" required>
                </div>

                <button class="btn btn-warning" onclick="return confirm('Resetar senha para 123456?')">
                    Resetar senha
                </button>
            @else
                <div class="mb-3">
                    <label class="form-label">Senha atual</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nova senha</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar nova senha</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-success">
                    Alterar senha
                </button>
            @endif
        </form>
    </div>
</div>
@endsection
