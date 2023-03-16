<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('lib/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/input-file.css') }}">

    <title>Document</title>
</head>

<body class="app sidebar-mini rtl">

    @include('components.header')

    @include('components.sidebar')

    <main>
        <div class="app-content">
            @yield('content')
        </div>
    </main>
    <script src="{{ asset('lib/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('lib/js/popper.min.js') }}"></script>
    <script src="{{ asset('lib/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('lib/js/plugins/pace.min.js') }}"></script>
    <script src="{{ asset('lib/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
