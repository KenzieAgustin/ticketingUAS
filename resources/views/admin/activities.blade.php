<html>
<head>
    <title>Aktivitas User</title>
</head>
<body>

<h2>Aktivitas User</h2>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('admin.users.index') }}">Kelola User</a> |
    <a href="{{ route('admin.activities.index') }}">Aktivitas</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<hr>

@if($activities->isEmpty())
    <p>Belum ada aktivitas.</p>
@else
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
            <th>IP Address</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($activities as $activity)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $activity->user->name ?? '-' }}</td>
            <td>{{ $activity->user->email ?? '-' }}</td>
            <td>{{ $activity->action }}</td>
            <td>{{ $activity->ip_address }}</td>
            <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>
{{ $activities->links() }}
@endif

</body>
</html>