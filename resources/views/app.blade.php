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
    <link rel="stylesheet" href="{{ asset('css/sort-btn.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.0/fc-4.2.2/fh-3.3.2/kt-2.8.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.css"
        rel="stylesheet" />

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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.0/fc-4.2.2/fh-3.3.2/kt-2.8.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.js">
    </script>
    @stack('scripts')
</body>

</html>
