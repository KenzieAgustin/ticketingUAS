<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .notif-badge { background: red; color: white; font-size: 11px; padding: 1px 6px; border-radius: 10px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-err { padding: 8px 12px; background: #f8d7da; border-left: 3px solid #f44336; margin-bottom: 16px; font-size: 14px; color: #721c24; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=text], input[type=email], input[type=password] { width: 100%; max-width: 340px; }
        .form-group { margin-bottom: 14px; }
        .section-title { font-size: 15px; font-weight: bold; margin: 16px 0 10px; }
        .btn { background: none; border: 1px solid #ccc; cursor: pointer; color: #333; padding: 4px 10px; font-size: 13px; border-radius: 4px; }
        .btn:hover { background: #f5f5f5; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Edit profil</p>

<hr>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert-err">
        @foreach ($errors->all() as $error)
            <p style="margin:0; padding:2px 0;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="section-title">Data diri</div>

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="form-group">
        <label>No. HP</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}">
    </div>
    <div class="form-group">
        <label>Avatar</label>
        @if($user->avatar_url)
            <img src="{{ $user->avatar_url }}" width="48" style="border-radius:50%; display:block; margin-bottom:6px;">
        @endif
        <input type="file" name="avatar" accept="image/*">
    </div>
    <button class="btn" type="submit">Simpan perubahan</button>
</form>

<hr>

<a href="{{ route('profile.show') }}" style="font-size:13px; color:#888; text-decoration:none;">← Kembali ke profil</a>

</body>
</html>