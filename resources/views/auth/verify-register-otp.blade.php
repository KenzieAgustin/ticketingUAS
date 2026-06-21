<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h2 { margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 20px; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=text] { width: 100%; padding: 7px 10px; margin: 4px 0 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 20px; letter-spacing: 6px; text-align: center; box-sizing: border-box; }
        button[type=submit] { padding: 8px 20px; background: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button[type=submit]:hover { background: #555; }
        .alert-error { padding: 8px 12px; background: #fdecea; border-left: 3px solid #e53935; margin-bottom: 16px; font-size: 14px; color: #c62828; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        p { font-size: 14px; }
    </style>
</head>
<body>

    <h2>Verifikasi Email</h2>
    <p class="subtitle">Masukkan kode OTP yang dikirim ke {{ $email }}</p>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <p style="margin: 2px 0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.verify-otp') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <label>Kode OTP</label>
        <input type="text" name="otp" maxlength="6" required autofocus>

        <button type="submit">Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('register.resend-otp') }}" style="margin-top:10px;">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" style="background:none; border:none; color:#888; text-decoration:underline; cursor:pointer; font-size:13px; padding:0;">Kirim ulang OTP</button>
    </form>

    <hr>
    <p><a href="{{ route('login') }}">← Kembali ke login</a></p>

</body>
</html>