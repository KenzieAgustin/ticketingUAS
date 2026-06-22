<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tulis Ulasan</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-error { padding: 8px 12px; background: #fee; border-left: 3px solid red; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        select, input[type=text], textarea { padding: 4px 8px; font-size: 14px; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('reviews.create') }}">Tulis Ulasan</a> |
    <a href="{{ route('reviews.index') }}">Ulasan Saya</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Tulis Ulasan</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert-error">
        <ul style="margin:0;padding-left:16px">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('customer.reviews.store') }}">
    @csrf

    Event:
    <select name="event_id" required>
        <option value="">-- Pilih Event --</option>
        @foreach($events as $event)
        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
        @endforeach
    </select><br><br>

    Order:
    <select name="order_id" required>
        <option value="">-- Pilih Order --</option>
        @foreach($orders as $order)
        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>{{ $order->order_number }}</option>
        @endforeach
    </select><br><br>

    Rating:
    <select name="rating" required>
        <option value="">-- Pilih Rating --</option>
        @for($i = 5; $i >= 1; $i--)
        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
        @endfor
    </select><br><br>

    Judul: <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Acara yang luar biasa!" style="width:400px" required><br><br>

    Ulasan:<br>
    <textarea name="body" rows="5" style="width:500px" placeholder="Ceritakan pengalaman kamu di PRJ 2026..." required>{{ old('body') }}</textarea><br><br>

    <button type="submit" style="background:#2563eb;color:white;padding:8px 20px;border:none;border-radius:4px;cursor:pointer;font-size:14px">Kirim Ulasan</button>
    &nbsp;<a href="{{ route('reviews.index') }}">Lihat Ulasan Saya</a>
</form>

</body>
</html>