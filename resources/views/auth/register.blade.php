<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h2 { margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 20px; }
        label { font-size: 14px; }
        input[type=text], input[type=email], input[type=password] { width: 100%; padding: 7px 10px; margin: 4px 0 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        button[type=submit] { padding: 8px 20px; background: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button[type=submit]:hover { background: #555; }
        .alert-error { padding: 8px 12px; background: #fdecea; border-left: 3px solid #e53935; margin-bottom: 16px; font-size: 14px; color: #c62828; }
        hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        p { font-size: 14px; }
    </style>
</head>
<body>

    <h2>Pekan Raya Jakarta</h2>
    <p class="subtitle">Buat akun baru</p>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <p style="margin: 2px 0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Nama</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label><br>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label><br>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Daftar</button>
    </form>

    <hr>
    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>

</body>
</html>