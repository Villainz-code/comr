@extends('layouts.admin')

@section('title', 'Semua Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Kelola semua pesanan customer')

@section('content')

<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
        <p class="text-gray-400 text-sm">{{ $orders->total() }} pesanan total</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-left">
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Ubah Status</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-800/20 transition-colors">
                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-medium">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-gray-400 max-w-[160px] truncate">
                        {{ $order->product->name }}
                        @if($order->selected_size)
                            <div class="text-xs text-gray-500 mt-1">Ukuran: {{ $order->selected_size }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center font-semibold">{{ $order->quantity }}</td>
                    <td class="px-6 py-4 font-semibold">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        <div class="text-xs text-gray-500 font-normal mt-1">{{ strtoupper($order->payment_method) }} | Ongkir Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($order->status === 'pending')
                            <span class="bg-yellow-900/50 text-yellow-300 text-xs px-2.5 py-1 rounded-full font-medium">Pending</span>
                        @elseif($order->status === 'processed')
                            <span class="bg-blue-900/50 text-blue-300 text-xs px-2.5 py-1 rounded-full font-medium">Diproses</span>
                        @elseif($order->status === 'shipped')
                            <span class="bg-purple-900/50 text-purple-300 text-xs px-2.5 py-1 rounded-full font-medium">🚚 Dikirim</span>
                        @elseif($order->status === 'cancelled')
                            <span class="bg-red-900/50 text-red-300 text-xs px-2.5 py-1 rounded-full font-medium">Dibatalkan</span>
                        @else
                            <span class="bg-green-900/50 text-green-300 text-xs px-2.5 py-1 rounded-full font-medium">Selesai</span>
                        @endif

                        @if($order->estimated_arrival)
                            <div class="text-xs text-blue-400 mt-1.5 font-medium">🚚 {{ $order->estimated_arrival }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status"
                                class="bg-black border border-gray-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-gray-400">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <select name="estimated_arrival"
                                class="bg-black border border-gray-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-gray-400 w-32">
                                <option value="" {{ is_null($order->estimated_arrival) || $order->estimated_arrival === '' ? 'selected' : '' }}>Pilih Estimasi Tiba</option>
                                <option value="Paket sedang menuju rumah" {{ $order->estimated_arrival === 'Paket sedang menuju rumah' ? 'selected' : '' }}>Paket sedang menuju rumah</option>
                                <option value="1-2 Hari Kerja" {{ $order->estimated_arrival === '1-2 Hari Kerja' ? 'selected' : '' }}>1-2 Hari Kerja</option>
                                <option value="2-3 Hari Kerja" {{ $order->estimated_arrival === '2-3 Hari Kerja' ? 'selected' : '' }}>2-3 Hari Kerja</option>
                                <option value="3-5 Hari Kerja" {{ $order->estimated_arrival === '3-5 Hari Kerja' ? 'selected' : '' }}>3-5 Hari Kerja</option>
                                <option value="5-7 Hari Kerja" {{ $order->estimated_arrival === '5-7 Hari Kerja' ? 'selected' : '' }}>5-7 Hari Kerja</option>
                                <option value="1-2 Minggu" {{ $order->estimated_arrival === '1-2 Minggu' ? 'selected' : '' }}>1-2 Minggu</option>
                                <option value="2-4 Minggu" {{ $order->estimated_arrival === '2-4 Minggu' ? 'selected' : '' }}>2-4 Minggu</option>
                            </select>
                            <button type="submit"
                                class="bg-white text-black text-xs font-semibold px-3 py-1.5 rounded hover:bg-gray-200 transition-all">
                                Update
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
