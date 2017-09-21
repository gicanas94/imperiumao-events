<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>{{ $title }} - Eventos ImperiumAO</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.ico') }}">
    </head>
    <body>
        <div class="container">

            @include('layouts.header')

            @yield('content')

        </div>
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
    </body>
</html>
