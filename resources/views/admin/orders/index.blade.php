@extends('layouts.admin')

@section('title', 'Semua Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Kelola semua pesanan customer')

@section('content')

<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <div class="flex items-center justify-between mb-3">
            <p class="text-gray-400 text-sm">{{ $orders->total() }} pesanan {{ request('status') ? '(' . ucfirst(request('status') === 'processed' ? 'Diproses' : (request('status') === 'completed' ? 'Selesai' : (request('status') === 'cancelled' ? 'Dibatalkan' : 'Pending'))) . ')' : 'total' }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.orders') }}"
               class="text-xs px-3 py-1.5 rounded-full border transition-all {{ !request('status') ? 'bg-white text-black border-white font-semibold' : 'text-gray-400 border-gray-700 hover:border-gray-500 hover:text-white' }}">
                Semua
            </a>
            <a href="{{ route('admin.orders', ['status' => 'pending']) }}"
               class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'pending' ? 'bg-yellow-900/50 text-yellow-300 border-yellow-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-yellow-800 hover:text-yellow-400' }}">
                Pending
            </a>
            <a href="{{ route('admin.orders', ['status' => 'processed']) }}"
               class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'processed' ? 'bg-blue-900/50 text-blue-300 border-blue-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-blue-800 hover:text-blue-400' }}">
                Diproses
            </a>
            <a href="{{ route('admin.orders', ['status' => 'completed']) }}"
               class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'completed' ? 'bg-green-900/50 text-green-300 border-green-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-green-800 hover:text-green-400' }}">
                Selesai
            </a>
            <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}"
               class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'cancelled' ? 'bg-red-900/50 text-red-300 border-red-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-red-800 hover:text-red-400' }}">
                Dibatalkan
            </a>
        </div>
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
                        @elseif($order->status === 'cancelled')
                            <span class="bg-red-900/50 text-red-300 text-xs px-2.5 py-1 rounded-full font-medium">Dibatalkan</span>
                        @else
                            <span class="bg-green-900/50 text-green-300 text-xs px-2.5 py-1 rounded-full font-medium">Selesai</span>
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
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
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
