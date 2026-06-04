<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h2>Selamat datang, {{ Auth::user()->name }}!</h2>

    @if (session('success'))
        <div>{{ session('success') }}</div><br>
    @endif

    <nav>
        <a href="{{ route('profile.show') }}">Profil Saya</a> |

        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a> |
        @endif

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    <hr>
    <p>Kamu berhasil login.</p>
</body>
</html>