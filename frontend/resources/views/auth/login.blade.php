@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">

        <h3 class="mb-3">Login</h3>

        @error('login')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form method="POST" action="/login">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input class="form-control" name="email" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label>Senha</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button class="btn btn-primary w-100">Entrar</button>
        </form>

    </div>
</div>
@endsection