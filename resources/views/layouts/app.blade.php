<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Мой сайт')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <header>
        <nav>
            <a href="{{ route('home') }}">Главная</a> |
            {{-- <a href="{{ route('about') }}">О нас</a> | --}}
            {{-- <a href="{{ route('contacts') }}">Контакты</a> --}}
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>ФИО: <strong>Некрасов А.О.</strong>, группа: <strong>241-3210</strong></p>
    </footer>

</body>
</html>
