<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Staff</title>
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
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
    <a href="{{ route('admin.gates.index') }}">Gate</a> |
    <a href="{{ route('admin.staff-assignments.index') }}">Jadwal Staff</a> |
    <a href="{{ route('admin.check-ins.index') }}">Check-in</a> |
    <a href="{{ route('admin.reviews.index') }}">Review</a> |
    <a href="{{ route('admin.sales-report.index') }}">Sales Report</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Jadwal Staff</h2>

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

<a href="#" onclick="document.getElementById('form-tambah').style.display='block'">+ Tambah Jadwal</a>
<br><br>

<div id="form-tambah" style="display:none">
    <h3>Tambah Jadwal Staff</h3>
    <form method="POST" action="{{ route('admin.staff-assignments.store') }}">
        @csrf
        Nama Staff:
        <select name="user_id" required>
            <option value="">-- Pilih Staff --</option>
            @foreach($staffs as $staff)
            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
            @endforeach
        </select><br><br>
        Gate:
        <select name="gate_id" required>
            <option value="">-- Pilih Gate --</option>
            @foreach($gates as $gate)
            <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
            @endforeach
        </select><br><br>
        Event:
        <select name="event_id" required>
            <option value="">-- Pilih Event --</option>
            @foreach($events as $event)
            <option value="{{ $event->id }}">{{ $event->name }}</option>
            @endforeach
        </select><br><br>
        Tanggal: <input type="date" name="assignment_date" required><br><br>
        Shift:
        <select name="shift" required>
            <option value="morning">Morning</option>
            <option value="afternoon">Afternoon</option>
            <option value="evening">Evening</option>
            <option value="full_day">Full Day</option>
        </select><br><br>
        Jam Mulai: <input type="time" name="shift_start" required><br><br>
        Jam Selesai: <input type="time" name="shift_end" required><br><br>
        Status:
        <select name="status">
            <option value="scheduled">Scheduled</option>
            <option value="active">Active</option>
        </select><br><br>
        Catatan: <textarea name="notes" rows="2"></textarea><br><br>
        <button type="submit">Simpan</button>
        <button type="button" onclick="document.getElementById('form-tambah').style.display='none'">Batal</button>
    </form>
</div>

<form method="GET">
    Tanggal: <input type="date" name="assignment_date" value="{{ request('assignment_date') }}">
    Shift:
    <select name="shift">
        <option value="">Semua Shift</option>
        @foreach(['morning','afternoon','evening','full_day'] as $s)
        <option value="{{ $s }}" {{ request('shift') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        @foreach(['scheduled','active','completed','absent'] as $s)
        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
    <a href="{{ route('admin.staff-assignments.index') }}">Reset</a>
</form>
<br>

@if($assignments->isEmpty())
    <p>Belum ada jadwal staff.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Nama Staff</th><th>Gate</th><th>Tanggal</th><th>Shift</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($assignments as $a)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $a->user->name ?? 'Unknown' }}</td>
            <td>{{ $a->gate->name ?? '-' }} ({{ $a->gate->code ?? '' }})</td>
            <td>{{ \Carbon\Carbon::parse($a->assignment_date)->format('d M Y') }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$a->shift)) }}</td>
            <td>{{ $a->shift_start }} – {{ $a->shift_end }}</td>
            <td>{{ ucfirst($a->status) }}</td>
            <td>
                <a href="#" onclick="document.getElementById('status-{{ $a->id }}').style.display='block'">Update Status</a> |
                <form action="{{ route('admin.staff-assignments.destroy', $a) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus jadwal ini?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <tr id="status-{{ $a->id }}" style="display:none;background:#f5f5f5">
            <td colspan="8">
                <form method="POST" action="{{ route('admin.staff-assignments.updateStatus', $a) }}">
                    @csrf @method('PATCH')
                    Status:
                    <select name="status">
                        @foreach(['scheduled','active','completed','absent'] as $s)
                        <option value="{{ $s }}" {{ $a->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    Catatan: <input type="text" name="notes" value="{{ $a->notes }}">
                    <button type="submit">Simpan</button>
                    <button type="button" onclick="document.getElementById('status-{{ $a->id }}').style.display='none'">Batal</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>