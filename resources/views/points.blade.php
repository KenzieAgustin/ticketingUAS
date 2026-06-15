<!DOCTYPE html>
<html>
<head>
    <title>Point Reward</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 50px auto; padding: 20px; }
        input { padding: 8px; margin: 10px 0; width: 100%; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 4px; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>🎯 Point Reward</h2>

    @php $user = \App\Models\User::first(); @endphp

    @if(!$user)
        <p style="color:red">❌ Tidak ada user di database.</p>
    @else
        <p>👤 User: <strong>{{ $user->name }}</strong></p>
        <p>⭐ Poin kamu: <strong>{{ $user->points ?? 0 }}</strong></p>
        <p><small>Info: Setiap Rp100.000 pembelian = 10 poin. Setiap 10 poin = diskon Rp10.000</small></p>

        <hr>

        <form method="POST" action="/redeem">
            @csrf
            <label>Jumlah Poin yang ditukar (kelipatan 10):</label>
            <input type="number" name="required_points" min="10" step="10" value="10">
            <button type="submit">🔄 Tukar Poin</button>
        </form>

        @if(session('success'))
            <br><div class="success">✅ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <br><div class="error">❌ {{ session('error') }}</div>
        @endif
    @endif
</body>
</html>
