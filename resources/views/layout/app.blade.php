<!DOCTYPE html>
<html class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@yield('title') - MASDA</title>
    <link rel="shortcut icon" href="{{ asset("media/favicons/favicon.png") }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset("media/favicons/favicon-192x192.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("media/favicons/apple-touch-icon-180x180.png") }}">
    <link rel="stylesheet" id="css-main" href="{{ asset("css/oneui.min.css") }}">
    <script src="{{ asset("js/lib/jquery.min.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("js/plugins/sweetalert2/sweetalert2.min.css") }}">
    <script src="{{ asset("js/plugins/sweetalert2/sweetalert2.min.js") }}"></script>
    @yield('head')
</head>
<body class="d-flex flex-column h-100">
    @yield('content')
    @yield('modal')
    <script src="{{ asset("js/oneui.app.min.js") }}"></script>
    @if(Session::has('alert'))
        <script>
            let timerInterval
            Swal.fire({
                icon: '{{ Session::get('alert')['type'] }}',
                title: '{{ Session::get('alert')['title'] }}',
                html: '{{ Session::get('alert')['message'] }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                willClose: () => {
                    clearInterval(timerInterval)
                }
            });
        </script>
    @endif
    {{-- Menentukan Active pada sidebar --}}
    <script src="{{ asset('js/pages/das_app.min.js') }}"></script>
    @yield('script')
</body>
</html>
