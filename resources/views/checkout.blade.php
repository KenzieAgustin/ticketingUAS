<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout PRJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center font-sans p-6 text-gray-900">

    @if(!isset($snapToken))
    <div class="w-full max-w-sm text-center">
        <p class="text-gray-500 text-sm">Tidak ada sesi pembayaran aktif.</p>
        <a href="/tickets" class="text-black underline text-sm mt-4 block">← Kembali ke daftar tiket</a>
    </div>

    @else
    <div class="w-full max-w-sm text-center">
        <h1 class="text-2xl font-bold mb-2 tracking-tight">Pembayaran</h1>
        <p class="text-sm text-gray-500 mb-8">Selesaikan pesanan tiket Anda</p>

        <div class="text-left space-y-4 mb-10 text-sm">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-gray-500">Order ID</span>
                <span class="font-medium">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-gray-500">Nama</span>
                <span class="font-medium">{{ $order->user->name }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2">
                <span class="font-semibold text-base">Total Bayar</span>
                <span class="font-bold text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <button id="pay-button"
            class="w-full bg-black hover:bg-gray-800 text-white font-semibold py-4 rounded-xl text-sm transition-colors">
            Bayar Sekarang
        </button>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){ window.location.href = '/my-orders'; },
                onPending: function(result){ alert("Menunggu pembayaran!"); },
                onError: function(result){ alert("Pembayaran gagal!"); }
            });
        };
    </script>
    @endif

</body>
</html>