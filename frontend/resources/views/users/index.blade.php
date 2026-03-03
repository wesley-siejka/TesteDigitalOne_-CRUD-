@extends('layouts.app')

@section('title', 'Usuários')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Lista de Usuários</h3>
    <a href="/users/create" class="btn btn-primary">Novo Usuário</a>
</div>

@if(empty($users))
    <div class="alert alert-info">
        Nenhum usuário encontrado.
    </div>
@else

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Nível</th>
            <th>Status</th>
            <th>Cidade</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user['id'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['tipo'] }}</td>
                <td>{{ $user['nivel'] }}</td>
                <td>{{ $user['status'] }}</td>
                <td>{{ $user['cidade'] ?? '-' }}</td>
                <td>{{ $user['estado'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endif

@endsection