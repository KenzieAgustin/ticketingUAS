<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; }
        label { font-size: 14px; color: #888; display: block; margin-bottom: 4px; }
        input[type=password] { width: 100%; padding: 6px; margin-bottom: 12px; }
        .btn { background: #333; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 4px; }
        .alert-err { padding: 8px 12px; background: #f8d7da; border-left: 3px solid #f44336; margin-bottom: 16px; font-size: 14px; color: #721c24; }
    </style>
</head>
<body>
    <h1>Reset Password</h1>
    <p style="font-size:14px; color:#888;">Buat password baru untuk {{ $email }}</p>

    @if ($errors->any())
        <div class="alert-err">
            @foreach ($errors->all() as $error)
                <p style="margin:0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <label>Password Baru</label>
        <div style="position:relative;">
            <input type="password" name="password" id="password" required
                style="width:100%; padding:6px; padding-right:35px; box-sizing:border-box; margin-bottom:12px;">
            <span onclick="togglePassword('password', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">
                Tampilkan
            </span>
        </div>

        <label>Konfirmasi Password</label>
        <div style="position:relative;">
            <input type="password" name="password_confirmation" id="password_confirmation" required
                style="width:100%; padding:6px; padding-right:35px; box-sizing:border-box; margin-bottom:12px;">
            <span onclick="togglePassword('password_confirmation', this)"
                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:12px; user-select:none;">
                Tampilkan
            </span>
        </div>

        <button class="btn" type="submit">Reset Password</button>
    </form>

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