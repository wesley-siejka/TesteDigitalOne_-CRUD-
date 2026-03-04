<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Digital One')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    @php
        $authUser = session('user'); // array
        $isAdmin = data_get($authUser, 'nivel') === 'admin';
        $authId = data_get($authUser, 'id');
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ $isAdmin ? '/users' : '/me' }}">DigitalOne</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/me">Meu Perfil</a>
                    </li>

                    @if ($isAdmin)
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Usuários</a>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav ms-auto">
                    @if ($authUser)
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                {{ data_get($authUser, 'email') }}
                            </span>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="/logout" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">Sair</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

</body>

</html>
