@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Riwayat Pesanan</h1>
            <p class="text-gray-400 text-sm mt-1">Semua pesanan Anda</p>
        </div>
        <a href="{{ route('user.shop') }}"
           class="inline-flex items-center bg-white text-black font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
            + Pesanan Baru
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
                    <p class="text-gray-500 text-xs mt-0.5">Jumlah: {{ $order->quantity }} item</p>
                    <p class="text-gray-500 text-xs mt-0.5 truncate">Pengiriman: {{ Str::limit($order->shipping_address, 50) }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-bold text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
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
        <p class="text-sm mt-2">Yuk mulai belanja di COMR Mini!</p>
        <a href="{{ route('user.shop') }}" class="inline-block mt-6 bg-white text-black font-semibold px-6 py-3 rounded-lg text-sm hover:bg-gray-200 transition-all">
            Mulai Belanja
        </a>
    </div>
    @endif

</div>
@endsection
