<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tiket PRJ 2026</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: 0 auto; }
        h2, h3 { border-bottom: 2px solid #333; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; font-size: 14px; }
        th { background: #f4f4f4; font-weight: bold; }
        tr:hover { background: #fafafa; }
        .stat-box { display: inline-block; border: 1px solid #ccc; padding: 14px 20px; border-radius: 4px; margin-right: 12px; margin-bottom: 16px; min-width: 140px; text-align: center; }
        .stat-box .number { font-size: 28px; font-weight: bold; color: #d00; }
        .stat-box .label { font-size: 12px; color: #666; margin-top: 4px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: bold; }
        .badge-ok { background: #e6ffe6; color: #1a7a1a; border: 1px solid #4caf50; }
        .badge-low { background: #fff8e6; color: #7a5000; border: 1px solid #f0a500; }
        .badge-habis { background: #ffe6e6; color: #800; border: 1px solid #d00; }
        .badge-aktif { background: #e6ffe6; color: #1a7a1a; border: 1px solid #4caf50; }
        .badge-nonaktif { background: #eee; color: #999; border: 1px solid #ccc; }
        a { color: #333; font-size: 14px; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 16px; text-decoration: none; color: #d00; font-weight: bold; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="/tickets">← Ke Halaman Tiket</a>
        <a href="/scan">Halaman Scan</a>
    </div>

    <h2>Dashboard Admin — PRJ 2026</h2>

    {{-- Statistik ringkasan --}}
    <h3>Ringkasan</h3>
    <div>
        <div class="stat-box">
            <div class="number">{{ $tickets->count() }}</div>
            <div class="label">Jenis Tiket</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $tickets->sum(fn($t) => $t->zones->count()) }}</div>
            <div class="label">Total Zona</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $tickets->flatMap(fn($t) => $t->zones)->sum('quota_total') }}</div>
            <div class="label">Total Kuota</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $tickets->flatMap(fn($t) => $t->zones)->sum('quota_remaining') }}</div>
            <div class="label">Sisa Kuota</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $activePromos->count() }}</div>
            <div class="label">Promo Aktif</div>
        </div>
    </div>

    {{-- Daftar tiket & zona --}}
    <h3>Daftar Tiket & Kuota Zona</h3>
    <table>
        <thead>
            <tr>
                <th>Jenis Tiket</th>
                <th>Harga Dasar</th>
                <th>Zona</th>
                <th>Harga Zona</th>
                <th>Kuota Total</th>
                <th>Sisa Kuota</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                @if($ticket->zones->count() > 0)
                    @foreach($ticket->zones as $zone)
                    <tr>
                        @if($loop->first)
                        <td rowspan="{{ $ticket->zones->count() }}">
                            <strong>{{ $ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</strong>
                        </td>
                        <td rowspan="{{ $ticket->zones->count() }}">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </td>
                        @endif
                        <td>{{ $zone->zone_name }}</td>
                        <td>Rp {{ number_format($zone->price, 0, ',', '.') }}</td>
                        <td>{{ number_format($zone->quota_total, 0, ',', '.') }}</td>
                        <td>{{ number_format($zone->quota_remaining, 0, ',', '.') }}</td>
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
                @else
                <tr>
                    <td><strong>{{ $ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</strong></td>
                    <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                    <td colspan="5" style="color:#aaa; font-style:italic;">Tidak ada zona</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- Daftar pricing rules --}}
    <h3>Pricing Rules / Promo</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Promo</th>
                <th>Tiket</th>
                <th>Tipe Diskon</th>
                <th>Nilai Diskon</th>
                <th>Berlaku Dari</th>
                <th>Berlaku Sampai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pricingRules as $rule)
            <tr>
                <td><strong>{{ $rule->rule_name }}</strong></td>
                <td>{{ $rule->ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</td>
                <td>{{ ucfirst($rule->discount_type) }}</td>
                <td>
                    @if($rule->discount_type === 'percentage')
                        {{ $rule->discount_value }}%
                    @else
                        Rp {{ number_format($rule->discount_value, 0, ',', '.') }}
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($rule->start_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($rule->end_date)->format('d M Y') }}</td>
                <td>
                    @if(\Carbon\Carbon::now()->between($rule->start_date, $rule->end_date))
                        <span class="badge badge-aktif">AKTIF</span>
                    @else
                        <span class="badge badge-nonaktif">TIDAK AKTIF</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="color:#aaa; text-align:center;">Belum ada pricing rule.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>