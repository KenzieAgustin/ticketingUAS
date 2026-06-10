<html>
<head>
    <title>Edit Profil</title>
</head>
<body>
    <h2>Edit Profil</h2>

    @if (session('success'))
        <div style="background:#d4edda; border:1px solid #c3e6cb; padding:10px; margin-bottom:15px; color:#155724; border-radius: 5px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#f8d7da; border:1px solid #f5c6cb; padding:10px; margin-bottom:15px; color:#721c24; border-radius: 5px;">
            @foreach ($errors->all() as $error)
                <p style="margin:0; padding: 2px 0;">❌ {{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <h3>Data Diri</h3>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nama:</label><br>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required><br><br>

        <label>No. HP:</label><br>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"><br><br>

        <label>Alamat:</label><br>
        <input type="text" name="address" value="{{ old('address', $user->address) }}"><br><br>

        <label>Avatar:</label><br>
        @if($user->avatar_url)
            <img src="{{ $user->avatar_url }}" alt="Avatar" width="60"><br>
        @endif
        <input type="file" name="avatar" accept="image/*"><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <hr>

    <h3>Ganti Password</h3>
    <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')

        <label>Password Saat Ini:</label><br>
        <input type="password" name="current_password" required><br><br>

        <label>Password Baru:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password Baru:</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Ganti Password</button>
    </form>

    <hr>
    <a href="{{ route('profile.show') }}">Kembali ke Profil</a>
</body>
</html>