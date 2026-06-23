<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h2 { margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 20px; }
        label { font-size: 14px; }
        input[type=email], input[type=password] { width: 100%; padding: 7px 10px; margin: 4px 0 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        button[type=submit] { padding: 8px 20px; background: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button[type=submit]:hover { background: #555; }
        .alert-error { padding: 8px 12px; background: #fdecea; border-left: 3px solid #e53935; margin-bottom: 16px; font-size: 14px; color: #c62828; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        p { font-size: 14px; }
    </style>
</head>
<body>

    <h2>Pekan Raya Jakarta</h2>
    <p class="subtitle">Masuk ke akun kamu</p>

    @if ($errors->any())
        @php $message = $errors->first('email'); @endphp
        @if(str_contains($message, 'terkunci'))
            <div style="padding: 8px 12px; background: #fff8e1; border-left: 3px solid #f0a500; margin-bottom: 16px; font-size: 14px; color: #8a6d00;">
                {{ $message }}
            </div>
        @else
            <div class="alert-error">{{ $message }}</div>
        @endif
    @endif

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label>Password</label><br>
        <div style="position:relative;">
            <input type="password" name="password" id="password" required
                style="width:100%; padding:7px 10px; margin:4px 0 14px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-sizing:border-box;">
            <span onclick="togglePassword('password', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">
                Tampilkan
            </span>
        </div>

        <label style="font-size:14px">
            <input type="checkbox" name="remember"> Ingat saya
        </label>
        <a href="{{ route('password.forgot') }}" style="font-size:13px; float:right; color:#888;">Lupa password?</a>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <hr>
    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>

<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        el.textContent = 'Sembunyikan';
    } else {
        input.type = 'password';
        el.textContent = 'Tampilkan';
    }
}
</script>

</body>
</html>