<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — TicketingUAS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0f1117; --surface: #171923; --border: #2a2f45;
            --accent: #6366f1; --accent-2: #818cf8;
            --text: #e2e8f0; --text-muted: #94a3b8;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
            padding: 20px;
        }
        .login-wrap {
            width: 100%; max-width: 400px;
        }
        .login-logo {
            text-align: center; margin-bottom: 32px;
        }
        .logo-icon {
            width: 52px; height: 52px; background: var(--accent);
            border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 24px;
            margin: 0 auto 12px;
        }
        .logo-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px; font-weight: 700; color: var(--text);
        }
        .logo-sub { font-size: 13px; color: var(--text-muted); margin-top: 3px; }
        .login-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 32px;
        }
        .login-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 19px; font-weight: 700; margin-bottom: 6px;
        }
        .login-sub { font-size: 13.5px; color: var(--text-muted); margin-bottom: 26px; }
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase;
            letter-spacing: .5px; margin-bottom: 7px;
        }
        .form-control {
            width: 100%; padding: 11px 14px;
            background: #0f1117; border: 1px solid var(--border);
            border-radius: 8px; color: var(--text); font-size: 14.5px;
            font-family: 'Inter', sans-serif; outline: none;
            transition: border-color .15s;
        }
        .form-control:focus { border-color: var(--accent); }
        .form-control::placeholder { color: var(--text-muted); }
        .btn-submit {
            width: 100%; padding: 12px;
            background: var(--accent); color: #fff;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer; margin-top: 8px;
            transition: background .15s;
        }
        .btn-submit:hover { background: var(--accent-2); }
        .alert {
            padding: 11px 14px; border-radius: 8px;
            font-size: 13.5px; margin-bottom: 18px;
            background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25);
            color: #f87171;
        }
        .divider {
            text-align: center; font-size: 12px; color: var(--text-muted);
            margin: 20px 0; position: relative;
        }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%;
            width: calc(50% - 24px); height: 1px; background: var(--border);
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-logo">
        <div class="logo-icon">🎫</div>
        <div class="logo-name">TicketingUAS</div>
        <div class="logo-sub">Sistem Manajemen Tiket Event</div>
    </div>

    <div class="login-card">
        <div class="login-title">Masuk ke Dashboard</div>
        <div class="login-sub">Gunakan akun admin Anda untuk melanjutkan.</div>

        @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
        @endif
        @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}" placeholder="admin@example.com" autofocus required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Masuk →</button>
        </form>
    </div>
</div>
</body>
</html>