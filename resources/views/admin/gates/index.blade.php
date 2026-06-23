<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Gate</title>
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
        hr { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px 12px; border-bottom: 2px solid #ddd; color: #888; font-weight: normal; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #fafafa; }
        .action-badge { font-size: 11px; padding: 2px 8px; border-radius: 10px; }
        .action-login { background: #e8f4ea; color: #2e7d32; }
        .action-logout { background: #fde8e8; color: #c00; }
        .action-update_profile { background: #e8f0fe; color: #1a73e8; }
        .action-change_password { background: #fff3cd; color: #856404; }
        .empty { color: #aaa; font-size: 14px; padding: 20px 0; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Log aktivitas user</p>

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
    <a href="{{ route('admin.sales-report.index') }}" class="{{ request()->routeIs('admin.sales-report*') ? 'active' : '' }}">Sales Report</a>
</div>

<hr>

<h2>Daftar Gate</h2>

@if($errors->any())
    <div class="alert-error">
        <strong>Gagal menyimpan:</strong>
        <ul>
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<a href="#" onclick="document.getElementById('form-tambah').style.display='block'">+ Tambah Gate</a>
<br><br>

<div id="form-tambah" style="display:none">
    <h3>Tambah Gate Baru</h3>
    <form method="POST" action="{{ route('admin.gates.store') }}">
        @csrf
        Kode: <input type="text" name="code" required><br><br>
        Nama: <input type="text" name="name" required><br><br>
        Tipe:
        <select name="type" required>
            <option value="main">Main</option>
            <option value="concert">Concert</option>
            <option value="exhibition">Exhibition</option>
            <option value="emergency">Emergency</option>
        </select><br><br>
        Status:
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="maintenance">Maintenance</option>
        </select><br><br>
        Deskripsi: <textarea name="description" rows="2"></textarea><br><br>
        <button type="submit">Simpan</button>
        <button type="button" onclick="document.getElementById('form-tambah').style.display='none'">Batal</button>
    </form>
</div>

<form method="GET">
    Tipe:
    <select name="type">
        <option value="">Semua Tipe</option>
        @foreach(['main','concert','exhibition','emergency'] as $t)
        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
    </select>
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        @foreach(['active','inactive','maintenance'] as $s)
        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
    <a href="{{ route('admin.gates.index') }}">Reset</a>
</form>
<br>

@if($gates->isEmpty())
    <p>Belum ada gate.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Kode</th><th>Nama</th><th>Tipe</th><th>Status</th><th>Staff</th><th>Check-in</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($gates as $gate)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $gate->code }}</td>
            <td><a href="{{ route('admin.gates.show', $gate) }}">{{ $gate->name }}</a></td>
            <td>{{ ucfirst($gate->type) }}</td>
            <td>{{ ucfirst($gate->status) }}</td>
            <td>{{ $gate->staff_assignments_count }}</td>
            <td>{{ $gate->check_ins_count }}</td>
            <td>
                <a href="{{ route('admin.gates.show', $gate) }}">Detail</a> |
                <a href="#" onclick="document.getElementById('edit-{{ $gate->id }}').style.display='block'">Edit</a> |
                <form action="{{ route('admin.gates.destroy', $gate) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus gate ini?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <tr id="edit-{{ $gate->id }}" style="display:none;background:#f5f5f5">
            <td colspan="8">
                <form method="POST" action="{{ route('admin.gates.update', $gate) }}">
                    @csrf @method('PUT')
                    Kode: <input type="text" name="code" value="{{ $gate->code }}" required>
                    Nama: <input type="text" name="name" value="{{ $gate->name }}" required>
                    Tipe: <select name="type">
                        @foreach(['main','concert','exhibition','emergency'] as $t)
                        <option value="{{ $t }}" {{ $gate->type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    Status: <select name="status">
                        @foreach(['active','inactive','maintenance'] as $s)
                        <option value="{{ $s }}" {{ $gate->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    Deskripsi: <input type="text" name="description" value="{{ $gate->description }}">
                    <button type="submit">Simpan</button>
                    <button type="button" onclick="document.getElementById('edit-{{ $gate->id }}').style.display='none'">Batal</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>