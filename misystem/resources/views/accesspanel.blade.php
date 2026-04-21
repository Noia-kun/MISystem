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
        .footer-text {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 0.9rem;
            color: #888;
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
        .btn {
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 10px;
            margin: 15px;
        }

    </style>
</head>
<body>
    <div class="card bg-white">
        <h1>MIS Office - System Access Panel</h1>
        <p>Accessing our systems with ease.</p>
        {{-- <form method="POST" action="{{ route('login.redirect') }}">
            @csrf
            <button type="submit" name="user_type" value="admin" class="btn btn-primary">Log In as Admin</button>
            <button type="submit" name="user_type" value="user" class="btn btn-secondary">Log In as User</button>
        </form> --}}
        <a href="http://192.168.20.11/dtr/employee" class="btn btn-primary">DTR Employee System</a>
        <a href="{{ url('/roomschedules') }}" class="btn btn-secondary">Room Reservations</a>
        <hr>
        <small class="text-muted">Developed by: RR © {{ date('Y') }} All Rights Reserved</small>
    </div>
</body>
</html>

