<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body{ background:#f5f6fa; }
        .sidebar{
            width:230px;
            position:fixed;
            top:0;left:0;bottom:0;
            background:#1f2933;
            color:#fff;
            padding:20px;
        }
        .sidebar a{
            display:block;
            padding:10px;
            color:#cbd5e1;
            border-radius:8px;
            margin-bottom:6px;
            text-decoration:none;
        }
        .sidebar a:hover{ background:#374151; color:white; }
        .content{
            margin-left:250px;
            padding:25px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="mb-4">Admin</h4>
    <a href="admin/bookings"><i class="fas fa-calendar me-2"></i>Bookings</a>
    <a href="admin/payments"><i class="fas fa-credit-card me-2"></i>Payments</a>
</div>

<div class="content">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
