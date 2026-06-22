<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tipe Tiket</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <p><a href="{{ url('/home') }}" style="color:#333; text-decoration:none; font-size:14px;">← Kembali ke Home</a></p>
    <h2>Tipe Tiket (Daftar Jenis Tiket PRJ 2026)</h2>
    <p>Menampilkan kategori utama tiket sebelum dibagi berdasarkan zona/kelas konser.</p>
    <hr>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; max-width: 600px;">
        <thead style="background-color: #f4f4f4;">
            <tr>
                <th width="50">No</th>
                <th>Tipe / Jenis Tiket</th>
                <th>Harga Dasar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $index => $ticket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $ticket->ticket_type === 'entry_only' ? 'Masuk Saja' : 'Masuk + Konser' }}</strong></td>
                <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ url('/tickets/' . $ticket->id . '/buy') }}">Beli Tiket →</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>