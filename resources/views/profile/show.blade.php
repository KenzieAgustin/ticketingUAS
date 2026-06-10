<html>
<head>
    <title>Profil</title>
</head>
<body>
    <h2>Profil Saya</h2>

    @if (session('success'))
        <div>{{ session('success') }}</div><br>
    @endif

    <img src="{{ $user->avatar_url }}" alt="Avatar" width="80"><br><br>

    <p><strong>Nama:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>No. HP:</strong> {{ $user->phone ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>

    <br>
    <a href="{{ route('profile.edit') }}">Edit Profil</a> |
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>