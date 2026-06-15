<!DOCTYPE html>
<html lang="id">
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

@if($order->status == 'paid' || $order->status == 'success')
    <div class="mt-6 pt-4 border-t border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Ajukan Refund</h3>
        <form action="{{ route('refund.store', $order->id) }}" method="POST">
            @csrf
            <textarea name="reason" rows="3" required placeholder="Tuliskan alasan refund (misal: Salah beli tiket, sakit, dll)"
                class="w-full border border-gray-400 p-2 mb-2 focus:outline-none focus:border-blue-600"></textarea>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-800 cursor-pointer text-sm">
                Kirim Pengajuan
            </button>
        </form>
    </div>

@elseif($order->status == 'refund_pending')
    <div class="mt-6 pt-4 border-t border-gray-300">
        <p class="text-orange-600 font-bold bg-orange-50 border border-orange-200 p-3">
            ⏳ Pengajuan refund Anda sedang direview oleh Admin.
        </p>
    </div>
@endif
