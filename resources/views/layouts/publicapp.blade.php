<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'MIS Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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
            background-color: #343a40;
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
            align-items: center;
            justify-content: center;
            height: 60px;
            margin-bottom: 20px;
        }
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
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 30px;
        }
        body.collapsed .content {
            margin-left: 60px;
        }
    </style>

</head>
<body class="collapsed">

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
