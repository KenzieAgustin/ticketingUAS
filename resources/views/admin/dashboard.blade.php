<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>

@if (session('success'))
    <div>{{ session('success') }}</div><br>
@endif

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<hr>

<p>Login sebagai: <strong>{{ Auth::user()->name }}</strong>
   — Role: <strong>{{ Auth::user()->role }}</strong>
</p>

<hr>

<h3>Kelola Role User</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role Sekarang</th>
            <th>Ubah Role</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>
                {{ $user->name }}
                @if ($user->id === Auth::id())
                    <small>(kamu)</small>
                @endif
            </td>
            <td>{{ $user->email }}</td>
            <td><strong>{{ $user->role }}</strong></td>
            <td>
                @if ($user->id !== Auth::id())
                    <form method="POST" action="{{ route('admin.role.update', $user) }}" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <select name="role">
                            <option value="customer"   {{ $user->role === 'customer'   ? 'selected' : '' }}>Customer</option>
                            <option value="staff_gate" {{ $user->role === 'staff_gate' ? 'selected' : '' }}>Staff Gate</option>
                            <option value="admin"      {{ $user->role === 'admin'      ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit">Simpan</button>
                    </form>
                @else
                    <small>—</small>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>
{{ $users->links() }}

</body>
</html>