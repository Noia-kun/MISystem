<!-- New Login Page Design -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MIS Office Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            top: -100px;
            left: -100px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 400px;
            height: 400px;
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

        .brand-tag {
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            font-size: 0.7rem;
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
            width: 30px;
            height: 1px;
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

        .left-heading span {
            color: var(--gold);
        }

        .left-desc {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.8;
            max-width: 380px;
            position: relative;
        }

        .decorative-line {
            position: absolute;
            top: 60px;
            right: 60px;
            width: 1px;
            height: 120px;
            background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        }

        /* Right Panel */
        .right-panel {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            background: var(--navy);
            position: relative;
        }

        .login-box {
            width: 100%;
            max-width: 360px;
            animation: fadeUp 0.6s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.8rem;
            color: var(--cream);
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 40px;
        }

        .field-label {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--muted);
            display: block;
            margin-bottom: 8px;
        }

        .field-wrap {
            margin-bottom: 24px;
        }

        .form-control-custom {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 14px 18px;
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .form-control-custom::placeholder {
            color: rgba(136,146,164,0.5);
        }

        .form-control-custom:focus {
            border-color: var(--gold);
            background: rgba(201,168,76,0.04);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--gold);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 8px;
        }

        .btn-login:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 36px 0 24px;
        }

        .footer-text {
            font-size: 0.75rem;
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
            Information<br>
            <span>Management</span><br>
            System
        </h1>

        <p class="left-desc">
            Manage and track office inventory, room scheduling, and asset lending — all in one place.
        </p>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="login-box">
            <h2 class="login-title">Welcome back.</h2>
            <p class="login-subtitle">Sign in to access your dashboard.</p>

            @if(session('error'))
                <div style="background:rgba(220,53,69,0.1);border:1px solid rgba(220,53,69,0.3);color:#f08080;border-radius:8px;padding:12px 16px;font-size:0.85rem;margin-bottom:20px;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="field-wrap">
                    <label class="field-label">Username</label>
                    <input class="form-control-custom" name="username" type="text" placeholder="Enter your username" autocomplete="off" required>
                </div>

                <div class="field-wrap">
                    <label class="field-label">Password</label>
                    <div style="position: relative;">
                        <input class="form-control-custom" name="password" id="password" type="password" placeholder="Enter your password" required style="padding-right: 46px;">
                        <span onclick="togglePassword()" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);">
                            <i class="fa fa-eye" id="eye-icon"></i>
                        </span>
                    </div>
                </div>

                <button class="btn-login" type="submit">Sign In</button>
            </form>

            <div class="divider"></div>
            <p class="footer-text">Developed by RR &copy; {{ date('Y') }} &nbsp;·&nbsp; All Rights Reserved</p>
        </div>
    </div>
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
