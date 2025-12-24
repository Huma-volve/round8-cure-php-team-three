<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Panel</title>

    <link rel="stylesheet" href="{{ asset('Admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


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
  <div id="system-toast" class="sys-toast">
    <span id="system-toast-text"></span>
</div>

<div id="toastx" class="toastx"></div>


<style>
    .sys-toast{
        position:fixed;
        top:80px;
        left:50%;
        transform:translateX(-50%) translateY(-40px);
        background:#0f172a;
        color:#e5e7eb;
        padding:14px 22px;
        border-radius:14px;
        border:1px solid rgba(148,163,184,.25);
        box-shadow:0 20px 60px rgba(0,0,0,.6);
        z-index:99999;
        opacity:0;
        pointer-events:none;
        transition:.35s ease;
    }
    .sys-toast.show{
        opacity:1;
        transform:translateX(-50%) translateY(0);
    }
    .sys-toast.success{border-color:#22c55e;color:#86efac}
    .sys-toast.error{border-color:#ef4444;color:#fca5a5}
</style>
<script>
window.toast = function(msg,type='success'){
    const t = document.getElementById('system-toast');
    t.className = 'sys-toast ' + type;
    document.getElementById('system-toast-text').innerText = msg;
    setTimeout(()=>t.classList.add('show'),10);
    setTimeout(()=>t.classList.remove('show'),3500);
}
</script>



<script src="{{ asset('Admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('Admin/dist/js/adminlte.min.js') }}"></script>


@stack('scripts')
</body>
</html>
