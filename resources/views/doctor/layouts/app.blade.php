<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Panel</title>

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">

    {{-- Navbar --}}
    @include('doctor.layouts.navbar')

    {{-- Sidebar --}}
    @include('doctor.layouts.sidebar')

    {{-- Content --}}
    <div class="content-wrapper p-3">
        @yield('content')
    </div>

</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

@stack('scripts')
</body>
</html>
