@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Riwayat Pesanan</h1>
            <p class="text-gray-400 text-sm mt-1">{{ request('status') ? ucfirst(request('status') === 'processed' ? 'Diproses' : (request('status') === 'shipped' ? 'Dikirim' : (request('status') === 'completed' ? 'Selesai' : (request('status') === 'cancelled' ? 'Dibatalkan' : 'Pending')))) : 'Semua pesanan Anda' }}</p>
        </div>
        <a href="{{ route('user.shop') }}"
           class="inline-flex items-center bg-white text-black font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
            + Pesanan Baru
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 flex-wrap mb-6">
        <a href="{{ route('user.orders') }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ !request('status') ? 'bg-white text-black border-white font-semibold' : 'text-gray-400 border-gray-700 hover:border-gray-500 hover:text-white' }}">
            Semua
        </a>
        <a href="{{ route('user.orders', ['status' => 'pending']) }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'pending' ? 'bg-yellow-900/50 text-yellow-300 border-yellow-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-yellow-800 hover:text-yellow-400' }}">
            Pending
        </a>
        <a href="{{ route('user.orders', ['status' => 'processed']) }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'processed' ? 'bg-blue-900/50 text-blue-300 border-blue-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-blue-800 hover:text-blue-400' }}">
            Diproses
        </a>
        <a href="{{ route('user.orders', ['status' => 'shipped']) }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'shipped' ? 'bg-purple-900/50 text-purple-300 border-purple-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-purple-800 hover:text-purple-400' }}">
            Dikirim
        </a>
        <a href="{{ route('user.orders', ['status' => 'completed']) }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'completed' ? 'bg-green-900/50 text-green-300 border-green-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-green-800 hover:text-green-400' }}">
            Selesai
        </a>
        <a href="{{ route('user.orders', ['status' => 'cancelled']) }}"
           class="text-xs px-3 py-1.5 rounded-full border transition-all {{ request('status') === 'cancelled' ? 'bg-red-900/50 text-red-300 border-red-700 font-semibold' : 'text-gray-400 border-gray-700 hover:border-red-800 hover:text-red-400' }}">
            Dibatalkan
        </a>
    </div>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden hover:border-gray-600 transition-colors">
            <div class="flex items-center justify-between px-6 py-3 bg-black/30 border-b border-gray-800/50">
                <div class="flex items-center space-x-4 text-xs text-gray-500">
                    <span class="font-mono">Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span>·</span>
                    <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if($order->status === 'pending')
                    <span class="bg-yellow-900/50 text-yellow-300 text-xs px-3 py-1 rounded-full font-medium">⏳ Pending</span>
                @elseif($order->status === 'processed')
                    <span class="bg-blue-900/50 text-blue-300 text-xs px-3 py-1 rounded-full font-medium">🔄 Diproses</span>
                @elseif($order->status === 'shipped')
                    <span class="bg-purple-900/50 text-purple-300 text-xs px-3 py-1 rounded-full font-medium">🚚 Dikirim</span>
                @elseif($order->status === 'cancelled')
                    <span class="bg-red-900/50 text-red-300 text-xs px-3 py-1 rounded-full font-medium">✕ Dibatalkan</span>
                @else
                    <span class="bg-green-900/50 text-green-300 text-xs px-3 py-1 rounded-full font-medium">✓ Selesai</span>
                @endif
            </div>
            <div class="p-5 flex items-center space-x-4">
                <div class="w-16 h-16 bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                    @if($order->product->image)
                        <img src="{{ asset('storage/' . $order->product->image) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm">{{ $order->product->name }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">Jumlah: {{ $order->quantity }} item @if($order->selected_size) | Ukuran: {{ $order->selected_size }} @endif</p>
                    <p class="text-gray-500 text-xs mt-0.5 truncate">Pengiriman: {{ Str::limit($order->shipping_address, 50) }}</p>
                    @if($order->estimated_arrival)
                        <p class="text-blue-400 text-xs mt-1 font-semibold">🚚 Estimasi Tiba: {{ $order->estimated_arrival }}</p>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-bold text-lg mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    
                    @if($order->status === 'pending')
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('user.order.edit', $order) }}" class="inline-flex items-center bg-gray-800 text-gray-300 hover:text-white px-3 py-1.5 rounded text-xs transition-colors border border-gray-700">
                            Edit
                        </a>
                        <form action="{{ route('user.order.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="inline-flex items-center bg-red-900/30 text-red-400 hover:text-red-300 hover:bg-red-900/50 px-3 py-1.5 rounded text-xs transition-colors border border-red-900/50">
                                Batalkan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif

    @else
    <div class="bg-[#111] border border-gray-800 rounded-xl py-20 text-center text-gray-500">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="font-medium text-lg text-gray-400">Belum ada pesanan</p>
        <p class="text-sm mt-2">Yuk mulai belanja di Sinestesia.co!</p>
        <a href="{{ route('user.shop') }}" class="inline-block mt-6 bg-white text-black font-semibold px-6 py-3 rounded-lg text-sm hover:bg-gray-200 transition-all">
            Mulai Belanja
        </a>
    </div>
    @endif

</div>
@endsection
