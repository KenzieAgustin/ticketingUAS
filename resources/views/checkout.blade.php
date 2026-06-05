<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f4f4f5] min-h-screen flex items-center justify-center font-sans p-4">

    <!-- Container Utama Card Tiket -->
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-sm overflow-hidden relative">

        <!-- Bagian Header Biru -->
        <div class="bg-[#2b61ea] p-6 text-white">
            <p class="text-[10px] font-bold tracking-widest uppercase mb-1.5 text-blue-100">E-Ticket</p>
            <h2 class="text-xl font-bold leading-snug">Selesaikan<br>Pembayaran Tiketmu</h2>
        </div>

        <!-- Efek Garis Putus-putus & Potongan Tiket -->
        <div class="relative flex items-center h-4 bg-white">
            <div class="absolute -left-3 w-6 h-6 bg-[#f4f4f5] rounded-full"></div>
            <div class="w-full border-t border-dashed border-gray-200 mx-5"></div>
            <div class="absolute -right-3 w-6 h-6 bg-[#f4f4f5] rounded-full"></div>
        </div>

        <!-- Detail Pesanan -->
        <div class="p-6 pt-3">
            <div class="space-y-4">
                <!-- Item: Nomor Order -->
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Nomor Order</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->order_number }}</span>
                </div>

                <!-- Item: Nama Pemesan -->
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Nama Pemesan</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</span>
                </div>

                <!-- Item: Nama Acara -->
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Nama Acara</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->event_name }}</span>
                </div>

                <!-- Item: Jumlah Tiket -->
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-400">Jumlah Tiket</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $order->quantity }} tiket</span>
                </div>

                <!-- Item: Total Bayar -->
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm text-gray-400">Total Bayar</span>
                    <span class="text-base font-bold text-[#2b61ea]">Rp {{ number_format($order->total_amount ?? 500000, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Bayar -->
            <button id="pay-button" class="w-full mt-6 bg-[#2b61ea] hover:bg-blue-700 text-white font-semibold text-sm py-3.5 rounded-xl transition duration-200 shadow-md shadow-blue-500/30">
                Bayar Sekarang
            </button>

            <!-- Teks Footer Aman via Midtrans -->
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
                    console.log('Sukses', result);
                },
                onPending: function(result){
                    console.log('Pending', result);
                },
                onError: function(result){
                    console.log('Error', result);
                }
            });
        };
    </script>
</body>
</html>
