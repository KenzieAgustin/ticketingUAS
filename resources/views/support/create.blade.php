<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tiket Support — PRJ 2026</title>
    <style>
        body { font-family: sans-serif; max-width: 640px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 4px; margin-top: 14px; }
        input[type=text], select, textarea { width: 100%; padding: 9px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; min-height: 120px; }
        .error { color: red; font-size: 12px; margin-top: 3px; }
        .btn { padding: 10px 24px; background: #c00; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; }
        .hint { font-size: 12px; color: #888; margin-top: 3px; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>
<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('support.index') }}">Support</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>
<hr>

<h2>Buat Tiket Support Baru</h2>

<form method="POST" action="{{ route('support.store') }}">
    @csrf

    <label for="subject">Subjek <span style="color:red">*</span></label>
    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="200" placeholder="Contoh: Tiket saya belum diterima" required>
    @error('subject') <p class="error">{{ $message }}</p> @enderror

    <label for="order_id">Order Terkait <span style="color:#888; font-weight:normal;">(opsional)</span></label>
    <select name="order_id" id="order_id">
        <option value="">-- Tidak terkait order tertentu --</option>
        @foreach($orders as $order)
            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                {{ $order->order_number }} — {{ $order->created_at->format('d M Y') }}
            </option>
        @endforeach
    </select>
    @error('order_id') <p class="error">{{ $message }}</p> @enderror

    <label for="message">Pesan <span style="color:red">*</span></label>
    <textarea name="message" id="message" maxlength="3000" required placeholder="Jelaskan masalah atau pertanyaan Anda...">{{ old('message') }}</textarea>
    <p class="hint">Maksimal 3000 karakter.</p>
    @error('message') <p class="error">{{ $message }}</p> @enderror

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">Kirim Tiket</button>
        <a href="{{ route('support.index') }}" style="margin-left:12px; font-size:14px; color:#555;">Batal</a>
    </div>
</form>

</body>
</html>