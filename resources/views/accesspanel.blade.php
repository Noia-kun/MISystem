<!-- <!DOCTYPE html>
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
        <small class="text-muted">Developed by RR © {{ date('Y') }} All Rights Reserved</small>
    </div>
</body>
</html>
 -->

 <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MIS Office - System Access Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a1628;
            --navy-mid: #112240;
            --gold: #c9a84c;
            --gold-light: #e8c97a;
            --cream: #f5f0e8;
            --muted: #8892a4;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--navy);
            min-height: 100vh;
            display: flex;
            font-family: 'DM Sans', sans-serif;
            overflow: hidden;
        }

        /* Left Panel */
        .left-panel {
            width: 55%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 60px;
            background: var(--navy-mid);
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px; left: -100px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Logo */
        .logo-wrap {
            position: relative;
            z-index: 2;
        }
 
        .logo-wrap img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .decorative-line {
            position: absolute;
            top: 60px; right: 60px;
            width: 1px; height: 120px;
            background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        }

        .brand-tag {
            font-size: 0.7rem;
            font-weight: 300;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 24px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-tag::before {
            content: '';
            width: 30px; height: 1px;
            background: var(--gold);
        }

        .left-heading {
            font-family: 'DM Serif Display', serif;
            font-size: 3.8rem;
            line-height: 1.1;
            color: var(--cream);
            position: relative;
            margin-bottom: 24px;
        }

        .left-heading span { color: var(--gold); }

        .left-desc {
            font-size: 1rem;
            color: var(--muted);
            line-height: 1.8;
            max-width: 380px;
            position: relative;
        }

        /* Right Panel */
        .right-panel {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            background: var(--navy);
        }

        .access-box {
            width: 100%;
            max-width: 360px;
            animation: fadeUp 0.6s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .access-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: var(--cream);
            margin-bottom: 8px;
        }

        .access-subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 40px;
        }

        /* Buttons */
        .btn-access {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.15s, background 0.2s;
            margin-bottom: 16px;
        }

        .btn-access:last-of-type {
            margin-bottom: 0;
        }

        .btn-access-primary {
            background: var(--gold);
            color: var(--navy);
        }

        .btn-access-primary:hover {
            background: var(--gold-light);
            color: var(--navy);
            transform: translateY(-2px);
        }

        .btn-access-secondary {
            background: rgba(255,255,255,0.05);
            color: var(--cream);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-access-secondary:hover {
            background: rgba(255,255,255,0.09);
            color: var(--cream);
            transform: translateY(-2px);
        }

        .btn-icon {
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .btn-text-wrap {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .btn-label {
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.2;
        }

        .btn-desc {
            font-size: 0.75rem;
            opacity: 0.65;
            margin-top: 2px;
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 36px 0 24px;
        }

        .footer-text {
            font-size: 0.78rem;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .left-panel { width: 100%; padding: 40px 30px 50px; }
            .left-heading { font-size: 2.5rem; }
            .right-panel { width: 100%; padding: 40px 30px; }
        }
    </style>
</head>
<body>

    <!-- Left Panel -->
    <div class="left-panel">
        <div class="grid-overlay"></div>
        <div class="decorative-line"></div>

        <!-- Logo at top -->
        <div class="logo-wrap">
            <img src="http://localhost/dtr/Web Logo(White).png" alt="Company Logo">
        </div>

        <div class="brand-tag">MIS Office System</div>

        <h1 class="left-heading">
            System<br>
            <span>Access</span><br>
            Panel
        </h1>

        <p class="left-desc">
            Choose a system to access. All platforms are managed under a single sign-on.
        </p>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="access-box">
            <h2 class="access-title">Where to next?</h2>
            <p class="access-subtitle">Select a system to continue.</p>

            <a href="http://localhost/dtr/employee" class="btn-access btn-access-primary">
                <span class="btn-icon"><i class="bi bi-clock"></i></span>
                <span class="btn-text-wrap">
                    <span class="btn-label">DTR Employee System</span>
                    <span class="btn-desc">Daily time record and attendance</span>
                </span>
            </a>

            <a href="{{ url('/roomschedules') }}" class="btn-access btn-access-secondary">
                <span class="btn-icon"><i class="bi bi-door-open"></i></span>
                <span class="btn-text-wrap">
                    <span class="btn-label">Room Reservations</span>
                    <span class="btn-desc">Book and manage room schedules</span>
                </span>
            </a>

            <div class="divider"></div>
            <p class="footer-text">Developed by RR &copy; {{ date('Y') }} &nbsp;·&nbsp; All Rights Reserved</p>
        </div>
    </div>

</body>
</html>
