<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">

    <div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h1>

        <form action="/my-orders" method="GET" class="flex items-center gap-2">
            <label for="sort" class="text-sm text-gray-600 font-medium">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()"
                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none shadow-sm cursor-pointer">
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Waktu (Terbaru)</option>
                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Waktu (Terlama)</option>
                <option value="tertinggi" {{ request('sort') == 'tertinggi' ? 'selected' : '' }}>Total Bayar (Tertinggi)</option>
                <option value="terendah" {{ request('sort') == 'terendah' ? 'selected' : '' }}>Total Bayar (Terendah)</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nomor Order</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <th class="px-6 py-4 text-right">Aksi</th>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">#{{ $order->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($order->status == 'paid')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full font-medium text-xs">Paid</span>
                            @else
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full font-medium text-xs">Pending</span>
                            @endif
                        <td class="px-6 py-4 text-right">
    <a href="{{ route('order.show', $order->id) }}" class="text-blue-600 font-medium hover:text-blue-800 transition">
        Lihat Tiket &rarr;
    </a>
</td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-10 text-gray-400">Belum ada riwayat pesanan.</div>
        @endif
    </div>

</body>
</html>
