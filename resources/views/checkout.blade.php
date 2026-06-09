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
    <div class="w-full max-w-sm">
        <h1 class="text-3xl font-bold mb-8 tracking-tight">Checkout Tiket PRJ</h1>

        <form action="/checkout" method="POST" id="checkout-form" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-black focus:bg-white transition-all">
                </div>

                <div>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" required placeholder="Jumlah Tiket"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-black focus:bg-white transition-all">
                </div>

                <div class="flex gap-2">
                    <input type="text" name="voucher_code" id="voucher_code" placeholder="Kode Promo"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm uppercase focus:outline-none focus:ring-1 focus:ring-black focus:bg-white transition-all">
                    <button type="button" id="btn-check-voucher"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 rounded-xl text-sm font-semibold transition-colors">Cek</button>
                </div>
                <p id="voucher-message" class="text-xs font-medium h-4"></p>
            </div>

            <div class="pt-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Harga Tiket</span>
                    <span>Rp 500.000</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp <span id="display-subtotal">500.000</span></span>
                </div>
                <div class="flex justify-between text-red-500">
                    <span>Diskon</span>
                    <span>- Rp <span id="display-discount">0</span></span>
                </div>
                <div class="flex justify-between text-base font-semibold pt-2 text-black">
                    <span>Total</span>
                    <span>Rp <span id="display-total">500.000</span></span>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-semibold py-4 rounded-xl text-sm transition-colors mt-4">
                Lanjut Bayar
            </button>
        </form>
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
                <span class="font-medium">{{ $order->nama }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-gray-500">Tiket</span>
                <span class="font-medium">{{ $order->quantity }}x</span>
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
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){ window.location.href = '/my-orders'; },
                onPending: function(result){ alert("Menunggu pembayaran!"); },
                onError: function(result){ alert("Pembayaran gagal!"); }
            });
        };
    </script>
    @endif

    <script>
        const hargaPerTiket = 500000;
        let currentDiscount = 0;

        const quantityInput = document.getElementById('quantity');
        const displaySubtotal = document.getElementById('display-subtotal');
        const displayDiscount = document.getElementById('display-discount');
        const displayTotal = document.getElementById('display-total');
        const voucherInput = document.getElementById('voucher_code');
        const voucherMessage = document.getElementById('voucher-message');
        const btnCheckVoucher = document.getElementById('btn-check-voucher');

        if (quantityInput) {
            function calculateTotal() {
                let qty = parseInt(quantityInput.value) || 1;
                let subtotal = qty * hargaPerTiket;
                let total = subtotal - currentDiscount;

                if (total < 1) total = 1;

                displaySubtotal.innerText = subtotal.toLocaleString('id-ID');
                displayDiscount.innerText = currentDiscount.toLocaleString('id-ID');
                displayTotal.innerText = total.toLocaleString('id-ID');
            }

            quantityInput.addEventListener('input', calculateTotal);

            btnCheckVoucher.addEventListener('click', async function() {
                const code = voucherInput.value.trim();

                if (!code) {
                    voucherMessage.innerText = "Masukkan kode dulu!";
                    voucherMessage.className = "text-xs font-medium text-red-500 h-4";
                    return;
                }

                voucherMessage.innerText = "Mengecek...";
                voucherMessage.className = "text-xs font-medium text-gray-500 h-4";

                try {
                    const response = await fetch('{{ route('check.voucher') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ voucher_code: code })
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        currentDiscount = result.discount_amount;
                        voucherMessage.innerText = result.message;
                        voucherMessage.className = "text-xs font-medium text-green-600 h-4";
                    } else {
                        currentDiscount = 0;
                        voucherMessage.innerText = result.message;
                        voucherMessage.className = "text-xs font-medium text-red-500 h-4";
                    }

                    calculateTotal();

                } catch (error) {
                    voucherMessage.innerText = "Terjadi kesalahan.";
                    voucherMessage.className = "text-xs font-medium text-red-500 h-4";
                }
            });
        }
    </script>
</body>
</html>
