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
    </style>
</head>
<body>

    <div class="nav">
        <a href="/tickets">← Ke Daftar Tiket</a>
        <a href="/admin/tickets">Admin Dashboard</a>
        <a href="/scan">Halaman Scan</a>
    </div>

    <h2>Quota Tracker — PRJ 2026</h2>
    <p style="color:#666; font-size:14px;">Monitoring sisa kuota zona secara realtime.</p>

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

    {{-- Tabel kuota per zona --}}
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
            @foreach($trackerData as $zone)
            @php
                $used = $zone->quota_total - $zone->quota_remaining;
                $pct  = $zone->quota_total > 0
                    ? round(($zone->quota_remaining / $zone->quota_total) * 100)
                    : 0;
                $barClass = $zone->quota_remaining <= 0 ? 'habis'
                    : ($zone->quota_remaining <= ($zone->quota_total * 0.1) ? 'low' : '');
            @endphp
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
            @endforeach
        </tbody>
    </table>

</body>
</html>