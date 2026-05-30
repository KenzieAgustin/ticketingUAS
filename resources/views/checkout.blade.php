<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Tiket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #eef0f5;
            --card: #ffffff;
            --text: #1a1a1a;
            --muted: #9098a9;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-light: #eff4ff;
            --border: #eaecf0;
            --success: #16a34a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        /* Top brand bar */
        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
            animation: fadeUp 0.4s ease both;
        }

        .brand-icon {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-icon svg { color: white; }

        .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        /* Steps indicator */
        .steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 24px;
            animation: fadeUp 0.4s 0.05s ease both;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
        }

        .step.active { color: var(--accent); }
        .step.done { color: var(--success); }

        .step-num {
            width: 22px; height: 22px;
            border-radius: 50%;
            background: var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
        }

        .step.active .step-num {
            background: var(--accent);
            color: white;
        }

        .step.done .step-num {
            background: var(--success);
            color: white;
        }

        .step-line {
            width: 36px;
            height: 2px;
            background: var(--border);
            margin: 0 4px;
        }

        .step-line.done { background: var(--success); }

        /* Main card */
        .wrapper {
            width: 100%;
            max-width: 420px;
            animation: fadeUp 0.5s 0.1s ease both;
            opacity: 0;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .ticket {
            background: var(--card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
        }

        /* Header */
        .ticket-header {
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
            padding: 28px 28px 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -20px; right: 60px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .ticket-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .ticket-title {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .ticket-subtitle {
            margin-top: 6px;
            font-size: 13px;
            opacity: 0.7;
            font-weight: 500;
        }

        /* Timer */
        .timer-bar {
            background: rgba(255,255,255,0.12);
            margin: 0 28px;
            margin-top: 16px;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
        }

        .timer-value {
            font-weight: 700;
            font-size: 13px;
            color: #fde68a;
        }

        /* Perforation */
        .perforation {
            height: 24px;
            background: var(--card);
            position: relative;
            display: flex;
            align-items: center;
        }

        .perforation::before {
            content: '';
            position: absolute;
            left: -12px;
            width: 24px; height: 24px;
            background: var(--bg);
            border-radius: 50%;
        }

        .perforation::after {
            content: '';
            position: absolute;
            right: -12px;
            width: 24px; height: 24px;
            background: var(--bg);
            border-radius: 50%;
        }

        .dashed-line {
            flex: 1;
            border-top: 2px dashed var(--border);
            margin: 0 20px;
        }

        /* Body */
        .ticket-body {
            padding: 4px 28px 28px;
        }

        /* Event banner */
        .event-banner {
            background: var(--accent-light);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .event-icon svg { color: white; }

        .event-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .event-type {
            font-size: 12px;
            color: var(--accent);
            font-weight: 500;
            margin-top: 1px;
        }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-of-type { border-bottom: none; }

        .info-label {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-label svg { opacity: 0.5; }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        /* Total section */
        .total-section {
            background: var(--accent-light);
            border-radius: 12px;
            padding: 14px 16px;
            margin: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 13px;
            color: var(--accent);
            font-weight: 600;
        }

        .total-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.02em;
        }

        /* Button */
        .pay-btn {
            width: 100%;
            padding: 15px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            color: white;
            background: var(--accent);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .pay-btn:hover {
            background: var(--accent-hover);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }

        .pay-btn:active { transform: scale(0.98); }

        /* Secure note */
        .secure-note {
            margin-top: 14px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* Bottom help text */
        .help-text {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            animation: fadeUp 0.5s 0.2s ease both;
            opacity: 0;
        }

        .help-text a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Brand -->
<div class="brand">
    <div class="brand-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
        </svg>
    </div>
    <span class="brand-name">Tiketku</span>
</div>

<!-- Steps -->
<div class="steps">
    <div class="step done">
        <div class="step-num">✓</div>
        <span>Pilih Tiket</span>
    </div>
    <div class="step-line done"></div>
    <div class="step done">
        <div class="step-num">✓</div>
        <span>Data Diri</span>
    </div>
    <div class="step-line"></div>
    <div class="step active">
        <div class="step-num">3</div>
        <span>Pembayaran</span>
    </div>
</div>

<!-- Ticket Card -->
<div class="wrapper">
    <div class="ticket">
        <div class="ticket-header">
            <div class="ticket-badge">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                E-Ticket
            </div>
            <div class="ticket-title">Selesaikan<br>Pembayaran Tiketmu</div>
            <div class="ticket-subtitle">Segera selesaikan sebelum waktu habis</div>

            <div class="timer-bar" style="margin: 16px 0 0 0;">
                <span>⏱ Batas waktu pembayaran</span>
                <span class="timer-value" id="timer">23:59:59</span>
            </div>
        </div>

        <div class="perforation">
            <div class="dashed-line"></div>
        </div>

        <div class="ticket-body">

            <!-- Event banner -->
            <div class="event-banner">
                <div class="event-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <div class="event-name">{{ $order->event_name ?? 'Event Name' }}</div>
                    <div class="event-type">Konser · Jakarta</div>
                </div>
            </div>

            <!-- Info rows -->
            <div class="info-row">
                <span class="info-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Nomor Order
                </span>
                <span class="info-value">#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Nama Pemesan
                </span>
                <span class="info-value">{{ $order->user->name ?? 'Budi' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Jumlah Tiket
                </span>
                <span class="info-value">{{ $order->quantity ?? 1 }} tiket</span>
            </div>
            <div class="info-row">
                <span class="info-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Status
                </span>
                <span class="info-value" style="color: #d97706; background: #fef3c7; padding: 2px 10px; border-radius: 20px; font-size: 12px;">Menunggu Bayar</span>
            </div>

            <!-- Total -->
            <div class="total-section">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            <button id="pay-button" class="pay-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Bayar Sekarang
            </button>

            <div class="secure-note">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Pembayaran aman & terenkripsi via Midtrans
            </div>
        </div>
    </div>
</div>

<div class="help-text">
    Ada masalah? <a href="#">Hubungi Bantuan</a>
</div>

<script type="text/javascript">
    // Countdown timer
    var timeLeft = 23 * 3600 + 59 * 60 + 59;
    var timerEl = document.getElementById('timer');

    var interval = setInterval(function() {
        if (timeLeft <= 0) { clearInterval(interval); timerEl.textContent = '00:00:00'; return; }
        timeLeft--;
        var h = Math.floor(timeLeft / 3600);
        var m = Math.floor((timeLeft % 3600) / 60);
        var s = timeLeft % 60;
        timerEl.textContent = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    }, 1000);

    // Pay button
    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}');
    });
</script>

</body>
</html>
