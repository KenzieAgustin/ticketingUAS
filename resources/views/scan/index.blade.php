<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <title>Scan Tiket - PRJ 2026</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 500px; margin: 0 auto; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 8px; }
        input[type=text] { width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px; }
        .btn { padding: 10px 24px; background: #d00; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; margin-right: 8px; }
        .btn-secondary { background: #555; }
        .btn-checkin { background: #1a7a1a; margin-top: 10px; display: inline-block; color: white; padding: 10px 24px; border-radius: 4px; font-size: 14px; text-decoration: none; }
        .btn-checkin:hover { background: #145a14; }
        .result { padding: 14px; border-radius: 4px; margin-top: 16px; font-size: 14px; }
        .result.valid { background: #f0fff0; border: 1px solid #4caf50; color: #1a5c1a; }
        .result.invalid { background: #fff0f0; border: 1px solid #d00; color: #800; }
        .result.used { background: #fffbe6; border: 1px solid #f0a500; color: #7a5000; }
        .tab-btn { padding: 8px 20px; cursor: pointer; border: 1px solid #ccc; background: #f4f4f4; border-radius: 4px 4px 0 0; font-size: 14px; }
        .tab-btn.active { background: white; border-bottom-color: white; font-weight: bold; }
        .tab-panel { border: 1px solid #ccc; padding: 16px; border-radius: 0 4px 4px 4px; margin-bottom: 16px; }
        .history-item { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee; font-size: 13px; }
        #video-area { width: 100%; background: #000; border-radius: 4px; margin-bottom: 10px; display: none; }
        a.back { color: #333; font-size: 14px; text-decoration: none; }
        a.back:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        td { padding: 4px 0; font-size: 13px; }
        td:first-child { color: #666; width: 40%; }
    </style>
</head>
<body>

    <p><a href="/" class="back">← Kembali ke Home</a></p>
    <h2>Scan Tiket PRJ 2026</h2>
    <p style="color:#666; font-size:14px;">Validasi tiket masuk pengunjung.</p>

    {{-- Tab --}}
    <div>
        <button class="tab-btn active" onclick="switchTab('manual')" id="tab-manual">Input Booking Code</button>
        <button class="tab-btn" onclick="switchTab('qr')" id="tab-qr">Scan QR</button>
    </div>

    {{-- Panel Manual --}}
    <div class="tab-panel" id="panel-manual">
        <label style="font-size:14px; font-weight:bold;">Booking Code:</label>
        <input type="text" id="booking-input" placeholder="PRJ2026-XXXXXX" maxlength="14"
               oninput="this.value = this.value.toUpperCase()"
               onkeydown="if(event.key==='Enter') doValidate()">
        <button class="btn" onclick="doValidate()">Validasi</button>
    </div>

    {{-- Panel QR --}}
    <div class="tab-panel" id="panel-qr" style="display:none;">
        <video id="video-area" autoplay playsinline></video>
        <button class="btn" id="btn-camera" onclick="startCamera()">Aktifkan Kamera</button>
        <hr style="margin: 14px 0;">
        <p style="font-size:13px; color:#666;">QR tidak terbaca? Input manual:</p>
        <input type="text" id="qr-fallback" placeholder="PRJ2026-XXXXXX" maxlength="14"
               oninput="this.value = this.value.toUpperCase()"
               onkeydown="if(event.key==='Enter') doValidateQr()">
        <button class="btn btn-secondary" onclick="doValidateQr()">Cek</button>
    </div>

    {{-- Loading --}}
    <p id="loading" style="display:none; color:#888; font-size:14px;">Memeriksa tiket...</p>

    {{-- Result --}}
    <div id="result-area"></div>

    {{-- History --}}
    <h3 style="margin-top:24px;">Riwayat Scan</h3>
    <div id="history-list">
        <p style="color:#aaa; font-size:13px;">Belum ada scan.</p>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let lastValidCode = null;

        function switchTab(tab) {
            document.getElementById('panel-manual').style.display = tab === 'manual' ? '' : 'none';
            document.getElementById('panel-qr').style.display = tab === 'qr' ? '' : 'none';
            document.getElementById('tab-manual').classList.toggle('active', tab === 'manual');
            document.getElementById('tab-qr').classList.toggle('active', tab === 'qr');
            document.getElementById('result-area').innerHTML = '';
            lastValidCode = null;
        }

        function doValidate() {
            validate(document.getElementById('booking-input').value.trim());
        }
        function doValidateQr() {
            validate(document.getElementById('qr-fallback').value.trim());
        }

        function validate(code) {
            if (!code || code.length < 14) {
                showResult('invalid', 'Format booking code tidak valid.', null, code);
                return;
            }
            document.getElementById('loading').style.display = '';
            document.getElementById('result-area').innerHTML = '';
            lastValidCode = null;

            fetch('/tokens/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ booking_code: code })
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                const status = data.success ? 'valid' : (data.status || 'invalid');
                if (status === 'valid') lastValidCode = code;
                showResult(status, data.message, data.data, code);
                addHistory(code, status);
            })
            .catch(() => {
                document.getElementById('loading').style.display = 'none';
                showResult('invalid', 'Gagal terhubung ke server.', null, code);
            });
        }

        function showResult(status, message, data, code) {
            const labels = { valid: '✓ TIKET VALID', invalid: '✗ TIDAK VALID', used: '⚠ SUDAH DIPAKAI' };

            let detail = '';
            if (data) {
                detail = `<table>
                    ${data.ticket_type ? `<tr><td>Jenis Tiket</td><td>${data.ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser'}</td></tr>` : ''}
                    ${data.zone_name ? `<tr><td>Zona</td><td>${data.zone_name}</td></tr>` : ''}
                    ${data.booking_code ? `<tr><td>Booking Code</td><td>${data.booking_code}</td></tr>` : ''}
                </table>`;
            }

            // Tombol lanjut check-in hanya muncul kalau valid
            const checkinBtn = status === 'valid'
                ? `<br><br><a href="/staff/check-ins/scan?booking_code=${encodeURIComponent(code)}" class="btn-checkin">Lanjut Check-in →</a>
                   <br><br><a href="#" onclick="resetScan();return false;" style="font-size:13px; color:#555;">Scan tiket berikutnya</a>`
                : `<br><a href="#" onclick="resetScan();return false;" style="font-size:13px;">Coba lagi</a>`;

            document.getElementById('result-area').innerHTML = `
                <div class="result ${status}">
                    <strong>${labels[status] || status.toUpperCase()}</strong><br>
                    <span>${message || ''}</span>
                    ${detail}
                    ${checkinBtn}
                </div>`;
        }

        function resetScan() {
            document.getElementById('booking-input').value = '';
            document.getElementById('qr-fallback').value = '';
            document.getElementById('result-area').innerHTML = '';
            lastValidCode = null;
        }

        const scanHistory = [];
        function addHistory(code, status) {
            const time = new Date().toLocaleTimeString('id-ID');
            scanHistory.unshift({ code, status, time });
            const labels = { valid: 'Valid', invalid: 'Tidak Valid', used: 'Terpakai' };
            const colors = { valid: 'green', invalid: 'red', used: 'orange' };
            document.getElementById('history-list').innerHTML = scanHistory.slice(0, 5).map(h =>
                `<div class="history-item">
                    <span style="font-family:monospace;">${h.code}</span>
                    <span>${h.time}</span>
                    <span style="color:${colors[h.status]}">${labels[h.status]}</span>
                </div>`
            ).join('');
        }

        let scanInterval = null;

        function startCamera() {
            const video = document.getElementById('video-area');
            const btn = document.getElementById('btn-camera');

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showResult('invalid', 'Browser tidak mendukung kamera.', null, '');
                return;
            }

            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    video.srcObject = stream;
                    video.style.display = 'block';
                    btn.textContent = 'Hentikan Kamera';
                    btn.onclick = () => stopCamera(stream);

                    // Mulai scan QR setiap 500ms
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    scanInterval = setInterval(() => {
                        if (video.readyState === video.HAVE_ENOUGH_DATA) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imageData.data, imageData.width, imageData.height);
                            if (code) {
                                stopCamera(stream);
                                document.getElementById('qr-fallback').value = code.data;
                                validate(code.data);
                            }
                        }
                    }, 500);
                })
                .catch(() => showResult('invalid', 'Tidak bisa akses kamera. Gunakan input manual.', null, ''));
        }

        function stopCamera(stream) {
            const video = document.getElementById('video-area');
            const btn = document.getElementById('btn-camera');
            if (scanInterval) clearInterval(scanInterval);
            stream.getTracks().forEach(t => t.stop());
            video.style.display = 'none';
            btn.textContent = 'Aktifkan Kamera';
            btn.onclick = startCamera;
        }
    </script>

</body>
</html>