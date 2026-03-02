<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Digital One')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/users">Digital One</a>

        @if(session('api_token'))
            <form method="POST" action="/logout" class="ms-auto">
                @csrf
                <button class="btn btn-outline-light btn-sm">Sair</button>
            </form>
        @endif
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>