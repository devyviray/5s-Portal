<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '5s Portal') }}</title>
    <!-- Favicon -->
    <link href="{{ url('img/lafil-logo.png') }}" rel="icon" type="image/png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @stack('js')
    <!-- Scripts -->
    <script src="{{ asset('js/all.js') }}" defer></script>
    
</body>
</html>
