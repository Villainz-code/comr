@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data toko Sinestesia.co')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    {{-- Total Products --}}
    <div class="stat-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider">Produk</span>
        </div>
        <p class="text-4xl font-black">{{ $stats['total_products'] }}</p>
        <p class="text-gray-500 text-xs mt-1">Total produk</p>
        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-white text-xs mt-3 block hover:underline">Kelola produk →</a>
    </div>

    {{-- Total Categories --}}
    <div class="stat-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider">Kategori</span>
        </div>
        <p class="text-4xl font-black">{{ $stats['total_categories'] }}</p>
        <p class="text-gray-500 text-xs mt-1">Total kategori</p>
        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white text-xs mt-3 block hover:underline">Kelola kategori →</a>
    </div>

    {{-- Total Customers --}}
    <div class="stat-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider">Customer</span>
        </div>
        <p class="text-4xl font-black">{{ $stats['total_users'] }}</p>
        <p class="text-gray-500 text-xs mt-1">Total customer</p>
        <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-white text-xs mt-3 block hover:underline">Lihat customer →</a>
    </div>

    {{-- Total Orders --}}
    <div class="stat-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider">Pesanan</span>
        </div>
        <p class="text-4xl font-black">{{ $stats['total_orders'] }}</p>
        <p class="text-gray-500 text-xs mt-1">Total pesanan</p>
        <a href="{{ route('admin.orders') }}" class="text-gray-400 hover:text-white text-xs mt-3 block hover:underline">Lihat pesanan →</a>
    </div>
</div>

{{-- Order Status Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="bg-yellow-900/20 border border-yellow-800/40 rounded-xl p-5 flex items-center justify-between hover:border-yellow-600/60 hover:bg-yellow-900/30 transition-all cursor-pointer">
        <div>
            <p class="text-yellow-400 text-xs uppercase tracking-wider font-semibold mb-1">Pending</p>
            <p class="text-3xl font-black text-yellow-300">{{ $stats['pending_orders'] }}</p>
        </div>
        <div class="w-10 h-10 bg-yellow-900/50 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </a>
    <a href="{{ route('admin.orders', ['status' => 'processed']) }}" class="bg-blue-900/20 border border-blue-800/40 rounded-xl p-5 flex items-center justify-between hover:border-blue-600/60 hover:bg-blue-900/30 transition-all cursor-pointer">
        <div>
            <p class="text-blue-400 text-xs uppercase tracking-wider font-semibold mb-1">Diproses</p>
            <p class="text-3xl font-black text-blue-300">{{ $stats['total_orders'] - $stats['pending_orders'] - $stats['completed_orders'] }}</p>
        </div>
        <div class="w-10 h-10 bg-blue-900/50 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
    </a>
    <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="bg-green-900/20 border border-green-800/40 rounded-xl p-5 flex items-center justify-between hover:border-green-600/60 hover:bg-green-900/30 transition-all cursor-pointer">
        <div>
            <p class="text-green-400 text-xs uppercase tracking-wider font-semibold mb-1">Selesai</p>
            <p class="text-3xl font-black text-green-300">{{ $stats['completed_orders'] }}</p>
        </div>
        <div class="w-10 h-10 bg-green-900/50 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </a>
</div>

{{-- Recent Orders --}}
<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
        <h2 class="font-semibold text-sm">Pesanan Terbaru</h2>
        <a href="{{ route('admin.orders') }}" class="text-gray-400 hover:text-white text-xs border border-gray-700 hover:border-gray-500 px-3 py-1.5 rounded transition-all">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-left">
                    <th class="px-6 py-3 text-gray-500 text-xs font-semibold uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-gray-500 text-xs font-semibold uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-gray-500 text-xs font-semibold uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-gray-500 text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-gray-500 text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-800/20 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-gray-400 max-w-[180px] truncate">{{ $order->product->name }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($order->status === 'pending')
                            <span class="bg-yellow-900/50 text-yellow-300 text-xs px-2.5 py-1 rounded-full font-medium">Pending</span>
                        @elseif($order->status === 'processed')
                            <span class="bg-blue-900/50 text-blue-300 text-xs px-2.5 py-1 rounded-full font-medium">Diproses</span>
                        @else
                            <span class="bg-green-900/50 text-green-300 text-xs px-2.5 py-1 rounded-full font-medium">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-sm">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
