<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    @if ($errors->any())
        <div>
            <strong>Error!</strong> {{ $errors->first('email') }}
        </div>
        <br>
    @endif

    @if (session('success'))
        <div>{{ session('success') }}</div><br>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>
            <input type="checkbox" name="remember"> Ingat saya
        </label><br><br>

        <button type="submit">Login</button>
    </form>

    <hr>
    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</body>
</html>