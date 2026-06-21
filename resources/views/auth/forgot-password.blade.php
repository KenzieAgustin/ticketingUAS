<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=email] { width: 100%; padding: 6px; margin-bottom: 12px; }
        .btn { background: #333; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 4px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-err { padding: 8px 12px; background: #f8d7da; border-left: 3px solid #f44336; margin-bottom: 16px; font-size: 14px; color: #721c24; }
    </style>
</head>
<body>
    <h1>Lupa Password</h1>
    <p style="font-size:14px; color:#888;">Masukkan email kamu, kami akan kirim kode OTP.</p>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-err">
            @foreach ($errors->all() as $error)
                <p style="margin:0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.send-otp') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" required autofocus>
        <button class="btn" type="submit">Kirim Kode OTP</button>
    </form>

    <p style="margin-top:16px; font-size:13px;"><a href="{{ route('login') }}">← Kembali ke login</a></p>
</body>
</html>