@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black">COMR Shop</h1>
        <p class="text-gray-400 mt-1">Temukan koleksi terbaik untuk Anda</p>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl p-4 mb-8">
        <form method="GET" action="{{ route('user.shop') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk..."
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-gray-400 transition-colors">
            </div>

            {{-- Category Filter --}}
            <select name="category" onchange="this.form.submit()"
                class="bg-black border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </option>
                @endforeach
            </select>

            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('user.shop') }}"
                   class="text-gray-400 hover:text-white border border-gray-700 px-4 py-2.5 rounded-lg text-sm transition-all text-center flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Results count --}}
    <p class="text-gray-500 text-sm mb-6">{{ $products->total() }} produk ditemukan</p>

    {{-- Products Grid --}}
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($products as $product)
        <div class="card-hover bg-[#111] border border-gray-800 rounded-xl overflow-hidden group">
            {{-- Image --}}
            <div class="relative h-60 bg-gray-900 overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-700">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="text-xs">No Image</span>
                    </div>
                @endif
                <div class="absolute top-3 left-3">
                    <span class="bg-black/80 text-gray-300 text-xs px-2.5 py-1 rounded-full backdrop-blur-sm">{{ $product->category->name }}</span>
                </div>
                @if($product->stock <= 5 && $product->stock > 0)
                    <div class="absolute top-3 right-3">
                        <span class="bg-red-900/80 text-red-300 text-xs px-2.5 py-1 rounded-full">Stok terbatas</span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-5">
                <h2 class="font-semibold text-sm mb-1 leading-snug">{{ $product->name }}</h2>
                <p class="text-gray-500 text-xs mb-4 line-clamp-2">{{ $product->description ?: 'Produk premium COMR Mini.' }}</p>

                <div class="flex items-center justify-between mb-4">
                    <p class="font-bold text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-xs">Stok: <span class="{{ $product->stock <= 5 ? 'text-red-400' : 'text-gray-300' }}">{{ $product->stock }}</span></p>
                </div>

                <a href="{{ route('user.order.create', $product) }}"
                   id="order-btn-{{ $product->id }}"
                   class="w-full inline-flex items-center justify-center bg-white text-black font-semibold py-2.5 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Pesan Sekarang
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    {{ $products->appends(request()->query())->links() }}

    @else
    <div class="py-24 text-center text-gray-500">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-lg font-medium">Produk tidak ditemukan</p>
        <p class="text-sm mt-2">Coba ubah filter atau kata kunci pencarian</p>
    </div>
    @endif

</div>

<style>
.card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

@endsection
