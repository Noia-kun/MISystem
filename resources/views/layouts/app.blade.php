<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'MIS Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar .brand {
            font-family: 'DM Serif Display', serif;
            color: #c9a84c; /* gold */
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 30px;
        }
        footer {
            margin-top: 50px;
        }
    </style> --}}
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
            padding: 16px 10px 8px 10px; /* reduce bottom padding */
            margin-bottom: 8px; /* reduce margin */
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
        /* Hide logout button label and style when collapsed */
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
            <img class="logo-default" src="http://localhost/dtr/logo.png" 
                alt="Logo" style="height:35px; width:auto; object-fit:contain;">
            <img class="logo-hover" src="http://localhost/dtr/Web Logo(White).png" 
                alt="Logo" style="height:auto; width:180px; object-fit:contain; margin-bottom:8px;">
            <span class="label">MIS Inventory Management System</span>
        </div>
        <a href="{{ url('/dashboard') }}">
            <span class="icon"><i class="fa fa-house"></i></span>
            <span class="label">Dashboard</span>
        </a>
        <a href="{{ route('inventoryitems') }}">
            <span class="icon"><i class="fa fa-box"></i></span>
            <span class="label">Lent Items</span>
        </a>
        <a href="{{ route('officeinventory') }}">
            <span class="icon"><i class="fa fa-screwdriver-wrench"></i></span>
            <span class="label">Inventory Items</span>
        </a>
        <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#roomSchedulerMenu" role="button" aria-expanded="false" aria-controls="roomSchedulerMenu">
            <span class="icon"><i class="fa fa-school"></i></span>
            <span class="label">Room Scheduler</span>
            <i class="fa fa-caret-down ms-auto"></i>
        </a>
            <div class="collapse ms-3" id="roomSchedulerMenu">
                <a href="{{ route('roomscheduler.index') }}">
                    <span class="icon"><i class="fa fa-book"></i></span> Book a Room
                </a>
                <a href="{{ route('rooms.management') }}">
                    <span class="icon"><i class="fa fa-door-open"></i></span> Room Management
                </a>
            </div>
        <a href="{{ route('backlogs.index') }}">
        <span class="icon"><i class="fa fa-clock-rotate-left"></i></span>
        <span class="label"> Back Logs</span>
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
        <small>&copy; {{ date('Y') }} MIS Office Inventory Management System. All rights reserved.</small>
        <small>Version {{ config('app.version') }}</small>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    // Collapse on normal link click (not dropdown toggle)
    document.querySelectorAll('.sidebar a[href]:not([data-bs-toggle])').forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.add('collapsed');
            body.classList.add('collapsed');
        });
    });

    // Expand on hover
    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.remove('collapsed');
        body.classList.remove('collapsed');
    });

    // Collapse again when mouse leaves
    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.add('collapsed');
        body.classList.add('collapsed');
        const openDropdown = document.querySelector('.collapse.show');
    // close every collapse menu that is open
        document.querySelectorAll('.collapse.show').forEach(el => {
            const bs = bootstrap.Collapse.getOrCreateInstance(el);
            bs.hide();
        });
    });
    
</script>
</body>
</html>
