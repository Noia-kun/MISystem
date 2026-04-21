<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MIS Office Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #555;
        }

        .btn-primary {
            padding: 10px 25px;
            font-size: 1.1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="card bg-white">
        <h1>MIS Office - Inventory Management System</h1>
        <p>Manage and track office inventory with ease and efficiency.</p>
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <input name="username" placeholder="Username">
            <input name="password" type="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
        <!-- {{-- <a href="{{ url('/dashboard') }}" class="btn btn-primary">Log in as an Admin</a>
        <a href="{{ url('/user-dashboard') }}" class="btn btn-secondary">Log in as User</a> --}} -->
    </div>
</body>
</html>
