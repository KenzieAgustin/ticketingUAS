<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket | {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4 font-sans">

    <div class="max-w-sm w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative">

        <div class="bg-gradient-to-br from-gray-900 to-gray-700 p-6 text-white text-center">
            <h2 class="text-xs font-semibold tracking-widest uppercase opacity-80 mb-1">E-Ticket Masuk</h2>
            <h1 class="text-2xl font-bold">Acara Apa ?</h1>
        </div>

        <div class="relative flex justify-between items-center px-4 -mt-3">
            <div class="w-6 h-6 bg-gray-100 rounded-full -ml-7 shadow-inner"></div>
            <div class="w-full border-t-2 border-dashed border-gray-200"></div>
            <div class="w-6 h-6 bg-gray-100 rounded-full -mr-7 shadow-inner"></div>
        </div>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Nomor Order</p>
                    <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-medium">Status</p>
                    <p class="font-bold {{ $order->status == 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                        {{ strtoupper($order->status) }}
                    </p>
                </div>
            </div>

            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Tanggal Pembelian</p>
                    <p class="font-semibold text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-medium">Total</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 flex flex-col items-center">
                @php $token = $order->items->first()?->token ?? null; @endphp
                @if($token && $token->qr_code_path && file_exists(public_path($token->qr_code_path)))
                    <img src="{{ asset($token->qr_code_path) }}" alt="QR Code" class="w-40 h-40 mb-3">
                    <p class="text-xs font-mono tracking-widest text-gray-500">{{ $token->booking_code }}</p>
                @elseif($order->status === 'paid')
                    <p class="text-xs text-yellow-600 text-center">QR Code sedang digenerate, refresh sebentar lagi.</p>
                @else
                    <p class="text-xs text-gray-400 text-center">QR Code akan muncul setelah pembayaran selesai.</p>
                @endif
            </div>
        </div>

        <a href="/my-orders" class="block w-full text-center py-4 bg-gray-50 text-blue-600 font-semibold hover:bg-gray-100 transition border-t border-gray-100">
            Kembali ke Daftar Pesanan
        </a>
    </div>

    @if($order->status == 'paid' || $order->status == 'success')
    <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl mt-4">
        <h3 class="text-sm font-bold text-gray-800 mb-2 text-center">Ajukan Refund</h3>
        <form action="{{ route('refund.store', $order->id) }}" method="POST" class="flex flex-col gap-2">
            @csrf
            <textarea name="reason" rows="2" required placeholder="Tuliskan alasan refund..."
                class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:outline-none focus:border-blue-500"></textarea>
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded-lg text-xs transition-colors">
                Kirim Pengajuan
            </button>
        </form>
    </div>

@elseif($order->status == 'refund_pending')
    <div class="p-4 border-t border-gray-100 bg-orange-50 rounded-b-2xl mt-4 text-center">
        <p class="text-xs text-orange-600 font-bold">⏳ Pengajuan refund sedang direview Admin</p>
    </div>
@endif

</body>
</html>
