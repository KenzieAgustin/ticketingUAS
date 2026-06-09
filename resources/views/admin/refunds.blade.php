<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Refund</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; }
    </style>
</head>
<body class="bg-gray-200 p-8 text-sm">

    <div class="max-w-4xl mx-auto bg-white border border-gray-400 p-6">
        <h2 class="text-xl font-bold mb-4 border-b border-gray-400 pb-2">Daftar Pengajuan Refund</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-500 text-green-700 p-3 mb-4 font-bold">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-500 text-red-700 p-3 mb-4 font-bold">{{ session('error') }}</div>
        @endif

        <table class="w-full border-collapse border border-gray-400 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-400 p-2 text-left">Order ID</th>
                    <th class="border border-gray-400 p-2 text-left">Alasan Refund</th>
                    <th class="border border-gray-400 p-2 text-center">Status</th>
                    <th class="border border-gray-400 p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $refund)
                <tr>
                    <td class="border border-gray-400 p-2 font-bold">{{ $refund->order->order_number }}</td>
                    <td class="border border-gray-400 p-2">{{ $refund->reason }}</td>
                    <td class="border border-gray-400 p-2 text-center">
                        @if($refund->status == 'pending')
                            <span class="text-orange-600 font-bold uppercase">Pending</span>
                        @elseif($refund->status == 'approved')
                            <span class="text-green-600 font-bold uppercase">Disetujui</span>
                        @else
                            <span class="text-red-600 font-bold uppercase">Ditolak</span>
                        @endif
                    </td>
                    <td class="border border-gray-400 p-2 text-center">
                        @if($refund->status == 'pending')
                            <div class="flex gap-2 justify-center">
                                <form action="{{ route('admin.refunds.approve', $refund->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 border border-green-800 cursor-pointer text-xs">
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin.refunds.reject', $refund->id) }}" method="POST" onsubmit="return confirm('Yakin mau tolak refund ini?')">
                                    @csrf
                                    <input type="hidden" name="admin_note" value="Alasan tidak memenuhi kriteria.">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 border border-red-800 cursor-pointer text-xs">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="border border-gray-400 p-4 text-center font-bold">Belum ada pengajuan refund.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
