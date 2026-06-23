<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <title>Scan Check-in</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }

        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-error   { padding: 8px 12px; background: #fee; border-left: 3px solid red; margin-bottom: 16px; font-size: 14px; }

        /* Tab */
        .tab-btn { padding: 8px 20px; cursor: pointer; border: 1px solid #ccc; background: #f4f4f4; border-radius: 4px 4px 0 0; font-size: 14px; }
        .tab-btn.active { background: white; border-bottom-color: white; font-weight: bold; }
        .tab-panel { border: 1px solid #ccc; padding: 16px; border-radius: 0 4px 4px 4px; margin-bottom: 16px; }

        /* Form elements */
        label { font-size: 14px; font-weight: bold; display: block; margin-bottom: 4px; }
        input[type=text], select { width: 100%; padding: 9px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 12px; }
        .btn { padding: 10px 24px; background: #c00; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; margin-right: 8px; }
        .btn-secondary { background: #555; }
        .btn-submit { background: #1a7a1a; width: 100%; padding: 11px; font-size: 15px; font-weight: bold; border: none; border-radius: 4px; color: white; cursor: pointer; margin-top: 4px; }
        .btn-submit:hover { background: #145a14; }

        /* Camera */
        #video-area { width: 100%; border-radius: 4px; background: #000; display: none; margin-bottom: 10px; }
        #scan-status { font-size: 13px; color: #888; margin-bottom: 8px; min-height: 18px; }

        /* History */
        .history-item { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee; font-size: 13px; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('staff.gates.index') }}">Gate</a> |
    <a href="{{ route('staff.check-ins.scan') }}">Scan Check-in</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Scan Check-in</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

{{-- Hidden form yang disubmit setelah QR terbaca --}}
<form id="checkin-form" method="POST" action="{{ route('staff.check-ins.scan.post') }}">
    @csrf
    <input type="hidden" id="form-booking-code" name="booking_code">
    <input type="hidden" id="form-gate-id" name="gate_id">
    <input type="hidden" id="form-method" name="method">
</form>

{{-- Gate & Metode (shared antara tab manual dan kamera) --}}
<div style="margin-bottom: 16px;">
    <label for="shared-gate">Gate <span style="color:red">*</span></label>
    <select id="shared-gate" required>
        <option value="">-- Pilih Gate --</option>
        @foreach($gates as $gate)
            <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
        @endforeach
    </select>

    <label for="shared-method">Metode</label>
    <select id="shared-method">
        <option value="qr_scan">QR Scan</option>
        <option value="manual_code">Manual Code</option>
    </select>
</div>

{{-- Tabs --}}
<div>
    <button class="tab-btn active" onclick="switchTab('manual')" id="tab-manual">Input Manual</button>
    <button class="tab-btn" onclick="switchTab('camera')" id="tab-camera">📷 Kamera QR</button>
</div>

{{-- Panel Manual --}}
<div class="tab-panel" id="panel-manual">
    <label for="manual-code">Booking Code</label>
    <input type="text" id="manual-code" placeholder="PRJ2026-XXXXXX" maxlength="14"
           oninput="this.value = this.value.toUpperCase()"
           onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitCheckin(this.value.trim(), 'manual'); }">
    <button class="btn-submit" onclick="submitCheckin(document.getElementById('manual-code').value.trim(), 'manual')">
        Proses Check-in
    </button>
</div>

{{-- Panel Kamera --}}
<div class="tab-panel" id="panel-camera" style="display:none;">
    <video id="video-area" autoplay playsinline></video>
    <p id="scan-status">Kamera belum aktif.</p>
    <button class="btn" id="btn-camera" onclick="startCamera()">Aktifkan Kamera</button>
    <hr style="margin: 14px 0;">
    <p style="font-size:13px; color:#666;">QR tidak terbaca? Input manual:</p>
    <input type="text" id="camera-fallback" placeholder="PRJ2026-XXXXXX" maxlength="14"
           oninput="this.value = this.value.toUpperCase()"
           onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitCheckin(this.value.trim(), 'qr_scan'); }">
    <button class="btn btn-secondary" onclick="submitCheckin(document.getElementById('camera-fallback').value.trim(), 'qr_scan')">
        Proses Check-in
    </button>
</div>

{{-- Riwayat --}}
<h3 style="margin-top: 24px;">Riwayat Scan Sesi Ini</h3>
<div id="history-list">
    <p style="color:#aaa; font-size:13px;">Belum ada scan.</p>
</div>

<script>
    let scanInterval = null;
    const scanHistory = [];

    function switchTab(tab) {
        document.getElementById('panel-manual').style.display  = tab === 'manual' ? '' : 'none';
        document.getElementById('panel-camera').style.display  = tab === 'camera' ? '' : 'none';
        document.getElementById('tab-manual').classList.toggle('active', tab === 'manual');
        document.getElementById('tab-camera').classList.toggle('active', tab === 'camera');
        // Hentikan kamera kalau pindah tab
        if (tab !== 'camera') stopCameraIfActive();
    }

    function submitCheckin(bookingCode, methodOverride) {
        const gateId = document.getElementById('shared-gate').value;
        const method = methodOverride || document.getElementById('shared-method').value;

        if (!bookingCode || bookingCode.length < 14) {
            alert('Format booking code tidak valid. Contoh: PRJ2026-XXXXXX');
            return;
        }
        if (!gateId) {
            alert('Pilih gate terlebih dahulu.');
            return;
        }

        // Isi hidden form lalu submit
        document.getElementById('form-booking-code').value = bookingCode;
        document.getElementById('form-gate-id').value      = gateId;
        document.getElementById('form-method').value       = method;
        document.getElementById('checkin-form').submit();
    }

    // ── Kamera ──────────────────────────────────────────────────────────────

    function startCamera() {
        const video  = document.getElementById('video-area');
        const btn    = document.getElementById('btn-camera');
        const status = document.getElementById('scan-status');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            status.textContent = 'Browser tidak mendukung kamera.';
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.style.display = 'block';
                btn.textContent = 'Hentikan Kamera';
                btn.onclick = () => stopCamera(stream);
                status.textContent = 'Mendeteksi QR code...';

                const canvas = document.createElement('canvas');
                const ctx    = canvas.getContext('2d');

                scanInterval = setInterval(() => {
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        canvas.width  = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code && code.data.startsWith('PRJ2026-')) {
                            stopCamera(stream);
                            status.textContent = 'QR terbaca: ' + code.data + ' — memproses check-in...';
                            addHistory(code.data, 'scanned');
                            // Langsung submit check-in
                            submitCheckin(code.data, 'qr_scan');
                        }
                    }
                }, 500);
            })
            .catch(() => {
                status.textContent = 'Tidak bisa akses kamera. Gunakan input manual.';
            });
    }

    function stopCamera(stream) {
        const video  = document.getElementById('video-area');
        const btn    = document.getElementById('btn-camera');
        const status = document.getElementById('scan-status');
        if (scanInterval) { clearInterval(scanInterval); scanInterval = null; }
        if (stream) stream.getTracks().forEach(t => t.stop());
        video.style.display = 'none';
        btn.textContent = 'Aktifkan Kamera';
        btn.onclick = startCamera;
        status.textContent = 'Kamera dimatikan.';
    }

    function stopCameraIfActive() {
        const video = document.getElementById('video-area');
        if (video.srcObject) {
            stopCamera(video.srcObject);
        }
    }

    // ── Riwayat ─────────────────────────────────────────────────────────────

    function addHistory(code, status) {
        const time = new Date().toLocaleTimeString('id-ID');
        const labels = { scanned: 'Discan', success: 'Berhasil', failed: 'Gagal' };
        const colors = { scanned: '#888', success: 'green', failed: 'red' };
        scanHistory.unshift({ code, status, time });
        document.getElementById('history-list').innerHTML = scanHistory.slice(0, 8).map(h =>
            `<div class="history-item">
                <span style="font-family:monospace;">${h.code}</span>
                <span>${h.time}</span>
                <span style="color:${colors[h.status] || '#888'}">${labels[h.status] || h.status}</span>
            </div>`
        ).join('');
    }

    // Tandai hasil dari session (sukses/gagal) ke riwayat
    @if(session('success'))
        addHistory('{{ session('last_scanned_code', '—') }}', 'success');
    @endif
    @if(session('error'))
        addHistory('—', 'failed');
    @endif
</script>

</body>
</html>