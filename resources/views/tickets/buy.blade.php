<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Tiket - PRJ 2026</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: 0 auto; }
        h2, h3 { border-bottom: 1px solid #ddd; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background: #f4f4f4; }
        .zone-card { border: 1px solid #ccc; padding: 12px 16px; margin-bottom: 8px; cursor: pointer; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
        .zone-card:hover { background: #f9f9f9; }
        .zone-card.selected { border-color: #d00; background: #fff5f5; }
        .zone-card.sold-out { background: #eee; color: #999; cursor: not-allowed; }
        .zone-price { font-weight: bold; color: #333; }
        .zone-sisa { font-size: 12px; color: #888; margin-top: 2px; }
        .btn { padding: 10px 24px; background: #d00; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; }
        .btn:disabled { background: #aaa; cursor: not-allowed; }
        .promo-box { background: #f0fff0; border: 1px solid #4caf50; padding: 10px 14px; margin-bottom: 16px; border-radius: 4px; font-size: 14px; }
        .summary { border: 1px solid #ccc; padding: 14px; border-radius: 4px; margin-bottom: 16px; }
        .summary-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 14px; }
        .summary-row.total { border-top: 1px solid #ddd; margin-top: 6px; padding-top: 8px; font-weight: bold; }
        .summary-row.discount { color: green; }
        .total-price { color: #d00; }
        a { color: #333; font-size: 14px; }
    </style>
</head>
<body>

    <p><a href="/tickets">← Kembali ke daftar tiket</a></p>
    <h2>Beli Tiket PRJ 2026</h2>

    <table>
        <tr>
            <th>Jenis Tiket</th>
            <td>{{ $ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</td>
        </tr>
        <tr>
            <th>Harga Dasar</th>
            <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Promo aktif --}}
    @if(isset($activePromo) && $activePromo)
    <div class="promo-box">
        🎉 <strong>Promo: {{ $activePromo->rule_name }}</strong> —
        Diskon Rp {{ number_format($activePromo->discount_value, 0, ',', '.') }}
    </div>
    @endif

    {{-- Pilih zona (hanya entry_concert) --}}
    @if($ticket->ticket_type === 'entry_concert')
    <h3>Pilih Zona</h3>
    @foreach($ticket->zones as $zone)
    <div class="zone-card {{ $zone->quota_remaining <= 0 ? 'sold-out' : '' }}"
         data-zone-id="{{ $zone->id }}"
         data-zone-name="{{ $zone->zone_name }}"
         data-zone-price="{{ $zone->price }}"
         onclick="{{ $zone->quota_remaining > 0 ? 'selectZone(this)' : '' }}">
        <div>
            <strong>{{ $zone->zone_name }}</strong>
            @if($zone->quota_remaining <= 0)
                <span style="color:red;"> — HABIS</span>
            @endif
            <div class="zone-sisa">Sisa: {{ $zone->quota_remaining }} / {{ $zone->quota_total }}</div>
        </div>
        <div class="zone-price">
            Rp {{ number_format($zone->price, 0, ',', '.') }}
        </div>
    </div>
    @endforeach
    @endif

    {{-- Ringkasan harga --}}
    <h3>Ringkasan</h3>
    <div class="summary">
        <div class="summary-row">
            <span>Harga tiket</span>
            <span id="base-price-display">
                Rp {{ number_format($ticket->price, 0, ',', '.') }}
            </span>
        </div>
        <div class="summary-row discount" id="discount-row" style="display:none;">
            <span id="promo-label">Diskon</span>
            <span id="discount-amount">- Rp 0</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span class="total-price" id="final-price">
                Rp {{ number_format($ticket->price, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Form beli --}}
    <form method="POST" action="/orders">
        @csrf
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
        <input type="hidden" name="ticket_zone_id" id="form-zone-id" value="">
        <button type="submit" class="btn" id="btn-submit"
            {{ $ticket->ticket_type === 'entry_concert' ? 'disabled' : '' }}>
            Lanjut ke Pembayaran
        </button>
        @if($ticket->ticket_type === 'entry_concert')
        <p style="color:#888; font-size:13px;" id="zone-hint">* Pilih zona terlebih dahulu</p>
        @endif
    </form>

    <script>
        function selectZone(el) {
            // Deselect semua
            document.querySelectorAll('.zone-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');

            const zoneId    = el.dataset.zoneId;
            const zoneName  = el.dataset.zoneName;
            const zonePrice = parseInt(el.dataset.zonePrice);

            // Update form
            document.getElementById('form-zone-id').value = zoneId;
            document.getElementById('btn-submit').disabled = false;

            // Update tampilan harga dasar ke harga zona
            document.getElementById('base-price-display').textContent =
                'Rp ' + zonePrice.toLocaleString('id-ID');

            // Reset dulu
            document.getElementById('discount-row').style.display = 'none';
            document.getElementById('final-price').textContent =
                'Rp ' + zonePrice.toLocaleString('id-ID');

            const hint = document.getElementById('zone-hint');
            if (hint) hint.textContent = '* Zona dipilih: ' + zoneName;

            // Fetch harga dengan promo (kirim zone_id)
            fetchHarga(zoneId, zonePrice);
        }

        function fetchHarga(zoneId, zonePrice) {
            fetch(`/api/tickets/{{ $ticket->id }}/calculate-price?zone_id=${zoneId}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;

                    document.getElementById('final-price').textContent =
                        'Rp ' + Number(data.final_price).toLocaleString('id-ID');

                    if (data.discount > 0) {
                        document.getElementById('discount-row').style.display = 'flex';
                        document.getElementById('discount-amount').textContent =
                            '- Rp ' + Number(data.discount).toLocaleString('id-ID');
                        document.getElementById('promo-label').textContent =
                            data.promo_name || 'Diskon';
                    }
                })
                .catch(() => {}); // silent fail, harga zona tetap tampil
        }

        // Entry only: fetch harga langsung saat load
        @if($ticket->ticket_type === 'entry_only')
        fetchHarga(null, {{ $ticket->price }});
        @endif
    </script>

</body>
</html>