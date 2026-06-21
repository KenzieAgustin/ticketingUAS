<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=text] { width: 100%; padding: 6px; margin-bottom: 12px; font-size: 20px; letter-spacing: 4px; text-align: center; }
        .btn { background: #333; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 4px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-err { padding: 8px 12px; background: #f8d7da; border-left: 3px solid #f44336; margin-bottom: 16px; font-size: 14px; color: #721c24; }
    </style>
</head>
<body>
    <h1>Verifikasi OTP</h1>
    <p style="font-size:14px; color:#888;">Masukkan kode OTP yang dikirim ke {{ $email }}</p>

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

    <form method="POST" action="{{ route('password.verify-otp') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <label>Kode OTP</label>
        <input type="text" name="otp" maxlength="6" required autofocus>
        <button class="btn" type="submit">Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('password.send-otp') }}" style="margin-top:10px;">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" style="background:none; border:none; color:#888; text-decoration:underline; cursor:pointer; font-size:13px;">Kirim ulang OTP</button>
    </form>
</body>
</html>