@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">

    {{-- Welcome Header --}}
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-2">
            <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center text-lg font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-gray-400 text-sm">Selamat datang kembali 👋</p>
                <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-[#111] border border-gray-800 rounded-xl p-5 text-center hover:border-gray-600 transition-all">
            <p class="text-3xl font-black">{{ $stats['total_orders'] }}</p>
            <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Total Pesanan</p>
        </div>
        <div class="bg-yellow-900/20 border border-yellow-800/40 rounded-xl p-5 text-center hover:border-yellow-600/40 transition-all">
            <p class="text-3xl font-black text-yellow-300">{{ $stats['pending_orders'] }}</p>
            <p class="text-yellow-500/70 text-xs uppercase tracking-wider mt-1">Pending</p>
        </div>
        <div class="bg-blue-900/20 border border-blue-800/40 rounded-xl p-5 text-center hover:border-blue-600/40 transition-all">
            <p class="text-3xl font-black text-blue-300">{{ $stats['processed_orders'] }}</p>
            <p class="text-blue-500/70 text-xs uppercase tracking-wider mt-1">Diproses</p>
        </div>
        <div class="bg-green-900/20 border border-green-800/40 rounded-xl p-5 text-center hover:border-green-600/40 transition-all">
            <p class="text-3xl font-black text-green-300">{{ $stats['completed_orders'] }}</p>
            <p class="text-green-500/70 text-xs uppercase tracking-wider mt-1">Selesai</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        <a href="{{ route('user.shop') }}"
           class="bg-[#111] border border-gray-800 rounded-xl p-6 hover:border-gray-500 hover:bg-gray-900/50 transition-all group flex items-center space-x-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Toko</p>
                <p class="text-gray-500 text-xs">Lihat produk terbaru</p>
            </div>
        </a>
        <a href="{{ route('user.orders') }}"
           class="bg-[#111] border border-gray-800 rounded-xl p-6 hover:border-gray-500 hover:bg-gray-900/50 transition-all group flex items-center space-x-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Riwayat Pesanan</p>
                <p class="text-gray-500 text-xs">Lacak pesanan Anda</p>
            </div>
        </a>
        <a href="{{ route('user.profile') }}"
           class="bg-[#111] border border-gray-800 rounded-xl p-6 hover:border-gray-500 hover:bg-gray-900/50 transition-all group flex items-center space-x-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Profil Saya</p>
                <p class="text-gray-500 text-xs">Edit informasi akun</p>
            </div>
        </a>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
            <h2 class="font-semibold text-sm">Pesanan Terbaru</h2>
            <a href="{{ route('user.orders') }}" class="text-gray-400 hover:text-white text-xs border border-gray-700 hover:border-gray-500 px-3 py-1.5 rounded transition-all">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-800/50">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-800/20 transition-colors">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                        @if($order->product->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-sm truncate">{{ $order->product->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->quantity }} item · Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                    @if($order->status === 'pending')
                        <span class="bg-yellow-900/50 text-yellow-300 text-xs px-2.5 py-1 rounded-full">Pending</span>
                    @elseif($order->status === 'processed')
                        <span class="bg-blue-900/50 text-blue-300 text-xs px-2.5 py-1 rounded-full">Diproses</span>
                    @else
                        <span class="bg-green-900/50 text-green-300 text-xs px-2.5 py-1 rounded-full">Selesai</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-500 text-sm">
                <p>Belum ada pesanan</p>
                <a href="{{ route('user.shop') }}" class="text-white mt-2 inline-block hover:underline text-sm">Mulai belanja →</a>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
