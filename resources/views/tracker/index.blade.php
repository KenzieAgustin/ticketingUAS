<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quota Tracker - PRJ 2026</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; font-size: 14px; }
        th { background: #f4f4f4; font-weight: bold; }
        tr:hover { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: bold; }
        .badge-ok { background: #e6ffe6; color: #1a7a1a; border: 1px solid #4caf50; }
        .badge-low { background: #fff8e6; color: #7a5000; border: 1px solid #f0a500; }
        .badge-habis { background: #ffe6e6; color: #800; border: 1px solid #d00; }
        .badge-token-active { background: #e6f0ff; color: #1a4d7a; border: 1px solid #4c8cf0; }
        .badge-token-used { background: #eee; color: #555; border: 1px solid #ccc; }
        .badge-pay-paid, .badge-pay-settlement { background: #e6ffe6; color: #1a7a1a; border: 1px solid #4caf50; }
        .badge-pay-pending { background: #fff8e6; color: #7a5000; border: 1px solid #f0a500; }
        .badge-pay-expire, .badge-pay-refunded, .badge-pay-cancel { background: #ffe6e6; color: #800; border: 1px solid #d00; }
        .bar-bg { background: #eee; border-radius: 99px; height: 10px; width: 100%; min-width: 80px; }
        .bar-fill { height: 10px; border-radius: 99px; background: #4caf50; }
        .bar-fill.low { background: #f0a500; }
        .bar-fill.habis { background: #d00; }
        .stat-box { display: inline-block; border: 1px solid #ccc; padding: 12px 20px; border-radius: 4px; margin-right: 10px; margin-bottom: 16px; text-align: center; min-width: 120px; }
        .stat-box .number { font-size: 26px; font-weight: bold; color: #d00; }
        .stat-box .label { font-size: 12px; color: #666; margin-top: 2px; }
        a { color: #333; font-size: 14px; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 16px; color: #d00; font-weight: bold; }

        /* Detail booking per zona */
        details.zone-detail { margin: -1px 0 16px 0; border: 1px solid #ccc; border-top: none; }
        details.zone-detail summary { cursor: pointer; padding: 8px 12px; background: #fcfcfc; font-size: 13px; color: #555; list-style: none; }
        details.zone-detail summary::-webkit-details-marker { display: none; }
        details.zone-detail summary:before { content: "▶ "; font-size: 11px; }
        details.zone-detail[open] summary:before { content: "▼ "; }
        details.zone-detail summary:hover { background: #f4f4f4; }
        .detail-table { margin: 0; }
        .detail-table th, .detail-table td { font-size: 13px; padding: 6px 10px; }
        .empty-note { padding: 10px 12px; color: #aaa; font-style: italic; font-size: 13px; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="/tickets">← Ke Daftar Tiket</a>
        <a href="/admin/tickets">Admin Dashboard</a>
        <a href="/scan">Halaman Scan</a>
    </div>

    <h2>Quota Tracker — PRJ 2026</h2>
    <p style="color:#666; font-size:14px;">Monitoring sisa kuota zona secara realtime. Klik baris "Lihat detail booking" untuk melihat kode tiket yang sudah dibeli.</p>

    {{-- Ringkasan --}}
    <div style="margin-bottom: 20px;">
        <div class="stat-box">
            <div class="number">{{ $trackerData->count() }}</div>
            <div class="label">Total Zona</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $trackerData->sum('quota_total') }}</div>
            <div class="label">Total Kuota</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $trackerData->sum('quota_remaining') }}</div>
            <div class="label">Sisa Kuota</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $trackerData->sum('quota_total') - $trackerData->sum('quota_remaining') }}</div>
            <div class="label">Terjual</div>
        </div>
    </div>

    {{-- Tabel kuota per zona + detail booking --}}
    @foreach($trackerData as $zone)
    @php
        $used = $zone->quota_total - $zone->quota_remaining;
        $pct  = $zone->quota_total > 0
            ? round(($zone->quota_remaining / $zone->quota_total) * 100)
            : 0;
        $barClass = $zone->quota_remaining <= 0 ? 'habis'
            : ($zone->quota_remaining <= ($zone->quota_total * 0.1) ? 'low' : '');

        $zoneOrderItems = $orderItems->get($zone->id, collect());
    @endphp
    <table>
        <thead>
            <tr>
                <th>Zona</th>
                <th>Jenis Tiket</th>
                <th>Harga</th>
                <th>Terjual</th>
                <th>Sisa</th>
                <th>Total</th>
                <th>Progres</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $zone->zone_name }}</strong></td>
                <td>{{ $zone->ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</td>
                <td>Rp {{ number_format($zone->price, 0, ',', '.') }}</td>
                <td>{{ number_format($used, 0, ',', '.') }}</td>
                <td>{{ number_format($zone->quota_remaining, 0, ',', '.') }}</td>
                <td>{{ number_format($zone->quota_total, 0, ',', '.') }}</td>
                <td style="min-width: 100px;">
                    <div class="bar-bg">
                        <div class="bar-fill {{ $barClass }}" style="width: {{ $pct }}%"></div>
                    </div>
                    <span style="font-size:11px; color:#888;">{{ $pct }}%</span>
                </td>
                <td>
                    @if($zone->quota_remaining <= 0)
                        <span class="badge badge-habis">HABIS</span>
                    @elseif($zone->quota_remaining <= ($zone->quota_total * 0.1))
                        <span class="badge badge-low">HAMPIR HABIS</span>
                    @else
                        <span class="badge badge-ok">TERSEDIA</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <details class="zone-detail">
        <summary>Lihat detail booking — {{ $zone->zone_name }} ({{ $zoneOrderItems->flatMap(fn($oi) => $oi->tokens)->count() }} tiket)</summary>

        @if($zoneOrderItems->isEmpty())
            <div class="empty-note">Belum ada tiket yang dibeli untuk zona ini.</div>
        @else
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status Token</th>
                        <th>Status Pembayaran</th>
                        <th>Harga</th>
                        <th>Tanggal Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zoneOrderItems as $orderItem)
                        @forelse($orderItem->tokens as $token)
                        <tr>
                            <td><code>{{ $token->booking_code }}</code></td>
                            <td>{{ $orderItem->order->user->name ?? '-' }}</td>
                            <td>{{ $orderItem->order->user->email ?? '-' }}</td>
                            <td>
                                @php $tokenStatus = strtolower($token->status ?? ''); @endphp
                                <span class="badge badge-token-{{ $tokenStatus === 'used' ? 'used' : 'active' }}">
                                    {{ strtoupper($token->status ?? '-') }}
                                </span>
                            </td>
                            <td>
                                @php $payStatus = strtolower($orderItem->order->payment->status ?? ''); @endphp
                                <span class="badge badge-pay-{{ $payStatus ?: 'pending' }}">
                                    {{ strtoupper($orderItem->order->payment->status ?? '-') }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($zone->price, 0, ',', '.') }}</td>
                            <td>{{ optional($orderItem->created_at)->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                        @empty
                        {{-- OrderItem ada tapi token belum di-generate (misal webhook belum jalan) --}}
                        <tr>
                            <td colspan="7" style="color:#aaa; font-style:italic;">
                                Order #{{ $orderItem->order->order_number ?? $orderItem->order_id }} — token belum tergenerate.
                            </td>
                        </tr>
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        @endif
    </details>
    @endforeach

</body>
</html>
