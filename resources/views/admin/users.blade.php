<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav { font-size: 14px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .admin-nav { margin-top: 6px; padding: 6px 10px; background: #f5f5f5; border-left: 3px solid #888; font-size: 13px; }
        .admin-nav a { color: #555; text-decoration: none; margin-right: 10px; }
        .admin-nav a:hover { text-decoration: underline; }
        .admin-nav a.active { font-weight: bold; color: #333; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px 12px; border-bottom: 2px solid #ddd; color: #888; font-weight: normal; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #fafafa; }
        .role-badge { font-size: 11px; padding: 2px 8px; border-radius: 10px; }
        .role-admin { background: #fde8e8; color: #c00; }
        .role-staff_gate { background: #fff3cd; color: #856404; }
        .role-customer { background: #e8f4ea; color: #2e7d32; }
        select { font-size: 13px; padding: 3px 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: none; border: 1px solid #ccc; cursor: pointer; color: #333; padding: 3px 10px; font-size: 13px; border-radius: 4px; }
        .btn:hover { background: #f5f5f5; }
        .you { font-size: 11px; color: #aaa; margin-left: 4px; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Kelola user</p>

<nav>
    <a href="{{ route('home') }}">← Home</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00; padding:0; font-size:14px">Logout</button>
    </form>
</nav>

<div class="admin-nav">
    Admin:
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">Kelola User</a>
    <a href="{{ route('admin.activities.index') }}" class="{{ request()->routeIs('admin.activities*') ? 'active' : '' }}">Log Aktivitas</a>
    <a href="{{ route('admin.gates.index') }}" class="{{ request()->routeIs('admin.gates*') ? 'active' : '' }}">Gate</a>
    <a href="{{ route('admin.staff-assignments.index') }}" class="{{ request()->routeIs('admin.staff*') ? 'active' : '' }}">Staff</a>
    <a href="{{ route('admin.check-ins.index') }}" class="{{ request()->routeIs('admin.check*') ? 'active' : '' }}">Check-in</a>
    <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">Ulasan</a>
    <a href="{{ route('admin.refunds.index') }}" class="{{ request()->routeIs('admin.refunds*') ? 'active' : '' }}">Refund</a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">Dashboard</a>
</div>

<hr>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
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
                    <span class="you">(kamu)</span>
                @endif
            </td>
            <td>{{ $user->email }}</td>
            <td>
                <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
            </td>
            <td>
                @if ($user->id !== Auth::id())
                    <form method="POST" action="{{ route('admin.role.update', $user) }}" style="display:inline">
                        @csrf @method('PATCH')
                        <select name="role">
                            <option value="customer"   {{ $user->role === 'customer'   ? 'selected' : '' }}>Customer</option>
                            <option value="staff_gate" {{ $user->role === 'staff_gate' ? 'selected' : '' }}>Staff Gate</option>
                            <option value="admin"      {{ $user->role === 'admin'      ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button class="btn" type="submit">Simpan</button>
                    </form>
                @else
                    <span style="color:#ccc">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:16px; font-size:13px; color:#888;">
    {{ $users->links() }}
</div>

</body>
</html>