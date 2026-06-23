<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=password] { width: 100%; max-width: 340px; }
        .form-group { margin-bottom: 14px; }
        .btn { background: none; border: 1px solid #ccc; cursor: pointer; color: #333; padding: 4px 10px; font-size: 13px; border-radius: 4px; text-decoration: none; }
        .btn:hover { background: #f5f5f5; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-err { padding: 8px 12px; background: #f8d7da; border-left: 3px solid #f44336; margin-bottom: 16px; font-size: 14px; color: #721c24; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Ganti password</p>

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

<form method="POST" action="{{ route('profile.password') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Password saat ini</label>
        <div style="position:relative; max-width:340px;">
            <input type="password" name="current_password" id="current_password" required
                style="width:100%; padding:6px; padding-right:90px; box-sizing:border-box;">
            <span onclick="togglePassword('current_password', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">Tampilkan</span>
        </div>
    </div>

    <div class="form-group">
        <label>Password baru</label>
        <div style="position:relative; max-width:340px;">
            <input type="password" name="password" id="password" required
                style="width:100%; padding:6px; padding-right:90px; box-sizing:border-box;">
            <span onclick="togglePassword('password', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">Tampilkan</span>
        </div>
    </div>

    <div class="form-group">
        <label>Konfirmasi password baru</label>
        <div style="position:relative; max-width:340px;">
            <input type="password" name="password_confirmation" id="password_confirmation" required
                style="width:100%; padding:6px; padding-right:90px; box-sizing:border-box;">
            <span onclick="togglePassword('password_confirmation', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">Tampilkan</span>
        </div>
    </div>
    <button class="btn" type="submit">Ganti password</button>
</form>

<hr>
<a href="{{ route('profile.show') }}" style="font-size:13px; color:#888; text-decoration:none;">← Kembali ke profil</a>

<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        el.textContent = 'Sembunyikan';
    } else {
        input.type = 'password';
        el.textContent = 'Tampilkan';
    }
}
</script>

</body>
</html>