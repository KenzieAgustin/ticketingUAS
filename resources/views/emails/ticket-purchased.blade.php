<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket PRJ 2026</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; margin: 0; color: #222; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #0f172a; color: #fff; padding: 24px; text-align: center; }
        .header p { margin: 0; font-size: 12px; letter-spacing: 2px; color: #94a3b8; text-transform: uppercase; }
        .header h1 { margin: 6px 0 0; font-size: 22px; }
        .body { padding: 24px; }
        .greet { font-size: 14px; margin-bottom: 16px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 6px 0; font-size: 13px; }
        .info-table td:first-child { color: #777; width: 45%; }
        .ticket-box { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .ticket-box h3 { margin: 0 0 10px; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
        .qr-wrap { text-align: center; margin-top: 10px; }
        .qr-wrap img { width: 160px; height: 160px; }
        .booking-code { font-family: monospace; font-size: 13px; letter-spacing: 1px; color: #444; margin-top: 6px; }
        .footer { text-align: center; padding: 16px; font-size: 12px; color: #999; }
        .badge { display: inline-block; background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>E-Tiket Masuk</p>
            <h1>Pekan Raya Jakarta 2026</h1>
        </div>

        <div class="body">
            <p class="greet">Halo <strong>{{ $order->user->name ?? 'Pengunjung' }}</strong>, terima kasih sudah membeli tiket PRJ 2026! Tunjukkan QR code di bawah saat masuk gate.</p>

            <table class="info-table">
                <tr>
                    <td>Nomor Order</td>
                    <td><strong>{{ $order->order_number }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal Pembelian</td>
                    <td>{{ $order->created_at->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><span class="badge">{{ strtoupper($order->status) }}</span></td>
                </tr>
                <tr>
                    <td>Total Pembayaran</td>
                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>

            @foreach($order->items as $item)
                <div class="ticket-box">
                    <h3>
                        {{ $item->ticketZone?->ticket?->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}
                        @if($item->ticketZone)
                            &mdash; {{ $item->ticketZone->zone_name }}
                        @endif
                    </h3>

                    @forelse($item->tokens as $token)
                        <div class="qr-wrap">
                            @php
                                $qrFullPath = public_path($token->qr_code_path);
                            @endphp
                            @if(file_exists($qrFullPath))
                                <img src="{{ $message->embedData(file_get_contents($qrFullPath), $token->booking_code . '.png', 'image/png') }}" alt="QR Code">
                            @endif
                            <p class="booking-code">{{ $token->booking_code }}</p>
                        </div>
                    @empty
                        <p style="font-size:12px; color:#999;">QR Code sedang diproses, cek halaman "Tiket Saya" beberapa saat lagi.</p>
                    @endforelse
                </div>
            @endforeach

            <p style="font-size:12px; color:#888;">Simpan email ini sebagai bukti tiket masuk Anda. Booking code juga dapat dilihat di halaman "Tiket Saya" pada akun Anda.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Pekan Raya Jakarta. Email ini dikirim otomatis, mohon tidak membalas.
        </div>
    </div>
</body>
</html>