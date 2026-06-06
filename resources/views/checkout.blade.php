<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f4f4f5] min-h-screen flex flex-col items-center justify-center font-sans p-4 gap-6">

    @if(!isset($snapToken))
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Beli Tiket FTI UNTAR</h2>

        <form action="/checkout" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="text-xs text-gray-500 font-semibold uppercase">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama" required class="w-full border border-gray-200 rounded-lg p-3 mt-1 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="text-xs text-gray-500 font-semibold uppercase">Jumlah Tiket</label>
                <input type="number" name="quantity" value="1" min="1" required class="w-full border border-gray-200 rounded-lg p-3 mt-1 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="text-xs text-gray-500 font-semibold uppercase">Kode Promo (Opsional)</label>
                <input type="text" name="voucher_code" placeholder="Misal: UNTARJUARA" class="w-full border-2 border-dashed border-blue-300 rounded-lg p-3 mt-1 text-blue-700 uppercase focus:outline-none focus:border-blue-500 bg-blue-50/50">
            </div>

            <button type="submit" class="w-full mt-2 bg-gray-900 hover:bg-black text-white font-semibold py-3.5 rounded-xl transition duration-200 shadow-md">
                Hitung Total & Beli Tiket
            </button>
        </form>
    </div>

    @else
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg overflow-hidden relative">
        <div class="bg-[#2b61ea] p-6 text-white text-center">
            <p class="text-[10px] font-bold tracking-widest uppercase mb-1.5 text-blue-100">E-Ticket</p>
            <h2 class="text-xl font-bold leading-snug">Selesaikan<br>Pembayaran Tiketmu</h2>
        </div>

        <div class="relative flex items-center h-4 bg-white">
            <div class="absolute -left-3 w-6 h-6 bg-[#f4f4f5] rounded-full shadow-inner"></div>
            <div class="w-full border-t-2 border-dashed border-gray-200 mx-5"></div>
            <div class="absolute -right-3 w-6 h-6 bg-[#f4f4f5] rounded-full shadow-inner"></div>
        </div>

        <div class="p-6 pt-3">
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Nomor Order</span>
                    <span class="text-sm font-bold text-gray-800">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Nama Pemesan</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->nama }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Jumlah Tiket</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->quantity }} tiket</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm text-gray-400">Total Bayar</span>
                    <span class="text-lg font-bold text-[#2b61ea]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full mt-8 bg-[#2b61ea] hover:bg-blue-700 text-white font-semibold text-sm py-3.5 rounded-xl transition duration-200 shadow-md shadow-blue-500/30">
                Bayar Sekarang
            </button>

            <div class="mt-4 flex items-center justify-center gap-1.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-[11px] font-medium tracking-wide">Pembayaran aman via Midtrans</span>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = '/my-orders'; // Redirect ke riwayat pesanan
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        };
    </script>
    @endif

</body>
</html>
