<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; max-width: 500px; margin: 0 auto; padding: 20px;">
    <h2>Reset Password - PRJ</h2>
    <p>Kamu meminta reset password. Gunakan kode OTP berikut:</p>
    <div style="background:#f5f5f5; padding:20px; text-align:center; font-size:28px; font-weight:bold; letter-spacing:8px; border-radius:8px; margin:20px 0;">
        {{ $otp }}
    </div>
    <p>Kode ini berlaku selama <strong>10 menit</strong>.</p>
    <p style="color:#888; font-size:13px;">Jika kamu tidak meminta reset password, abaikan email ini.</p>
</body>
</html>