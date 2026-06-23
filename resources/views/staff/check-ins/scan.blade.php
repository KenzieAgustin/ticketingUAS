<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Check-in</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav { font-size: 14px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .staff-nav { margin-top: 6px; padding: 8px 12px; background: #fff8e1; border-left: 3px solid #f0a500; font-size: 14px; }
        .staff-nav a { color: #333; text-decoration: none; margin-right: 10px; }
        .staff-nav a:hover { text-decoration: underline; }
        .staff-nav a.active { font-weight: bold; color: #333; }
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

<div class="staff-nav">
    Staff:
    <a href="{{ route('staff.scan') }}">Scan Tiket</a>
    <a href="{{ route('staff.check-ins.scan') }}">Scan Check-in</a> |
    <a href="{{ route('staff.gates.index') }}">Gate</a> |
</div>

<hr>

<h2>Scan Check-in</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('staff.check-ins.scan') }}">
    @csrf
    Kode Booking: <input type="text" name="booking_code" required placeholder="Ketik atau scan..." autofocus><br><br>
    Gate:
    <select name="gate_id" required>
        <option value="">-- Pilih Gate --</option>
        @foreach($gates as $gate)
        <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
        @endforeach
    </select><br><br>
    Metode:
    <select name="method">
        <option value="qr_scan">QR Scan</option>
        <option value="manual_code">Manual Code</option>
    </select><br><br>
    <button type="submit">Proses Check-in</button>
</form>

</body>
</html>