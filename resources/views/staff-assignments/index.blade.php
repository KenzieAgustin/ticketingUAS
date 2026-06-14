<html>
<head><title>Jadwal Staff</title></head>
<body>

<h2>Jadwal Staff</h2>

<a href="{{ route('dashboard') }}">Dashboard</a> |
<a href="{{ route('gates.index') }}">Gate</a> |
<a href="{{ route('staff-assignments.index') }}">Jadwal Staff</a> |
<a href="{{ route('check-ins.index') }}">Check-in</a> |
<a href="{{ route('reviews.index') }}">Review</a> |
<a href="{{ route('sales-report.index') }}">Sales Report</a> |
<form method="POST" action="{{ route('logout') }}" style="display:inline">
    @csrf <button type="submit">Logout</button>
</form>

<br><br>

{{-- Tampilkan error validasi --}}
@if($errors->any())
    <div style="background:#fee;border:1px solid red;padding:10px;margin-bottom:10px">
        <strong>Gagal menyimpan:</strong>
        <ul>
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <p><strong>{{ session('success') }}</strong></p>
@endif

<a href="#" onclick="document.getElementById('form-tambah').style.display='block'">+ Tambah Jadwal</a>
<br><br>

<div id="form-tambah" style="display:none">
    <h3>Tambah Jadwal Staff</h3>
    <form method="POST" action="{{ route('staff-assignments.store') }}">
        @csrf
        Staff ID: <input type="number" name="user_id" required><br><br>
        Gate:
        <select name="gate_id" required>
            <option value="">-- Pilih Gate --</option>
            @foreach($gates as $gate)
            <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
            @endforeach
        </select><br><br>
        Event ID: <input type="number" name="event_id" required><br><br>
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

{{-- Filter --}}
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
    <a href="{{ route('staff-assignments.index') }}">Reset</a>
</form>
<br>

@if($assignments->isEmpty())
    <p>Belum ada jadwal staff.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Staff</th>
            <th>Gate</th>
            <th>Tanggal</th>
            <th>Shift</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
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
                <form action="{{ route('staff-assignments.destroy', $a) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus jadwal ini?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <tr id="status-{{ $a->id }}" style="display:none;background:#f5f5f5">
            <td colspan="8">
                <form method="POST" action="{{ route('staff-assignments.updateStatus', $a) }}">
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