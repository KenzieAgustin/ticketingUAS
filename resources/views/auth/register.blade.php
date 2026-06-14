<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Daftar Akun</h2>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        <br>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label>Nama:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Daftar</button>
    </form>

    <hr>
    <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</body>
</html>