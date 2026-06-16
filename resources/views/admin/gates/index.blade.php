<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Gate</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-error { padding: 8px 12px; background: #fee; border-left: 3px solid red; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('gates.index') }}">Gate</a> |
    <a href="{{ route('staff-assignments.index') }}">Jadwal Staff</a> |
    <a href="{{ route('check-ins.index') }}">Check-in</a> |
    <a href="{{ route('reviews.index') }}">Review</a> |
    <a href="{{ route('sales-report.index') }}">Sales Report</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

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
    <form method="POST" action="{{ route('gates.store') }}">
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
    <a href="{{ route('gates.index') }}">Reset</a>
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
            <td><a href="{{ route('gates.show', $gate) }}">{{ $gate->name }}</a></td>
            <td>{{ ucfirst($gate->type) }}</td>
            <td>{{ ucfirst($gate->status) }}</td>
            <td>{{ $gate->staff_assignments_count }}</td>
            <td>{{ $gate->check_ins_count }}</td>
            <td>
                <a href="{{ route('gates.show', $gate) }}">Detail</a> |
                <a href="#" onclick="document.getElementById('edit-{{ $gate->id }}').style.display='block'">Edit</a> |
                <form action="{{ route('gates.destroy', $gate) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus gate ini?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <tr id="edit-{{ $gate->id }}" style="display:none;background:#f5f5f5">
            <td colspan="8">
                <form method="POST" action="{{ route('gates.update', $gate) }}">
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