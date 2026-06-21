<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'MIS Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            transition: padding-left 0.3s;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #112240;
            padding-top: 20px;
            transition: width 0.3s;
            overflow-x: hidden;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar:hover {
            width: 250px;
        }
        .sidebar h4,
        .sidebar a span {
            display: inline-block;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .sidebar .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: auto;
            padding: 16px 10px 8px 10px;
            margin-bottom: 8px;
            font-family: 'DM Serif Display', serif;
            color: #c9a84c;
            font-size: 0.8rem;
            text-align: center;
            line-height: 1.4;
        }

        .logo-hover { display: none; }
        .logo-default { display: block; }

        .sidebar:hover .logo-default { display: none; }
        .sidebar:hover .logo-hover { display: block; }
        .sidebar.collapsed h4,
        .sidebar.collapsed a .label {
            opacity: 0;
            visibility: hidden;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
        }
        .sidebar.collapsed .fa-caret-down {
            opacity: 0;
            visibility: hidden;
            width: 0;
        }
        .sidebar.collapsed .brand .label {
            opacity: 0;
            visibility: hidden;
            width: 0;
        }
        .sidebar a {
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a .icon {
            min-width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar a:hover {                    
            background-color: #1a3a6b;
        }
        .content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 30px;
        }
        body.collapsed .content {
            margin-left: 60px;
        }
        .sidebar.collapsed .logout-button {
            display: none;
        }
    </style>
</head>
<body class="collapsed">

<!-- Sidebar -->
<div class="sidebar collapsed d-flex flex-column justify-content-between">
    <div>
        <div class="brand">
            <img class="logo-default" src="{{ url('../dtr/logo.png') }}" 
                alt="Logo" style="height:35px; width:auto; object-fit:contain;">
            <img class="logo-hover" src="{{ url('../dtr/Web Logo(White).png') }}" 
                alt="Logo" style="height:auto; width:180px; object-fit:contain; margin-bottom:8px;">
            <span class="label">Admin Portal</span>
        </div>
        <a href="{{ url('/admin-dashboard') }}">
            <span class="icon"><i class="fa fa-house"></i></span>
            <span class="label">Dashboard</span>
        </a>
        <a href="{{ url('/admin-leave-requests') }}">
            <span class="icon"><i class="fa fa-file-lines"></i></span>
            <span class="label">Leave Requests</span>
        </a>
        <a href="{{ url('/admin-inventory') }}">
            <span class="icon"><i class="fa fa-screwdriver-wrench"></i></span>
            <span class="label">Inventory</span>
        </a>
    </div>
    {{-- Logout Button at Bottom --}}
    <div class="p-3 logout-button">
        <form action="{{ route('logout') }}" method="GET">
            <button type="submit" class="btn btn-outline-light w-100 rounded-pill d-flex align-items-center justify-content-center">
                <span class="label">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="content">
    @yield('content')

    <!-- Footer -->
    <footer class="text-center text-muted">
        <hr>
        <small>Developed by RR &copy; {{ date('Y') }} MIS Office Information Management System. All rights reserved.</small>
        <small>Version {{ config('app.version') }}</small>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    document.querySelectorAll('.sidebar a[href]:not([data-bs-toggle])').forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.add('collapsed');
            body.classList.add('collapsed');
        });
    });

    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.remove('collapsed');
        body.classList.remove('collapsed');
    });

    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.add('collapsed');
        body.classList.add('collapsed');
        document.querySelectorAll('.collapse.show').forEach(el => {
            const bs = bootstrap.Collapse.getOrCreateInstance(el);
            bs.hide();
        });
    });
</script>
</body>
</html>