@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden bg-black min-h-[90vh] flex items-center">
    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 60px, #fff 60px, #fff 61px), repeating-linear-gradient(90deg, transparent, transparent 60px, #fff 60px, #fff 61px);"></div>
    </div>
    <div class="absolute top-20 right-20 w-96 h-96 bg-white opacity-[0.02] rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-64 h-64 bg-white opacity-[0.03] rounded-full blur-2xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="max-w-3xl">
            <div class="inline-flex items-center border border-gray-700 rounded-full px-4 py-1.5 mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                <span class="text-gray-300 text-xs font-medium tracking-wider uppercase">New Collection Available</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-none mb-6">
                WEAR YOUR<br>
                <span class="text-gray-400">IDENTITY</span>
            </h1>

            <p class="text-gray-400 text-lg md:text-xl mb-10 max-w-xl leading-relaxed">
                Koleksi pakaian premium COMR Mini. Dirancang untuk mereka yang menghargai gaya, kualitas, dan ekspresi diri.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('user.shop') }}"
                           class="inline-flex items-center justify-center bg-white text-black font-bold px-8 py-4 rounded-sm text-sm tracking-wider uppercase hover:bg-gray-200 transition-all hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-white/20">
                            Explore Shop
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center bg-white text-black font-bold px-8 py-4 rounded-sm text-sm tracking-wider uppercase hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                            Go to Dashboard →
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center bg-white text-black font-bold px-8 py-4 rounded-sm text-sm tracking-wider uppercase hover:bg-gray-200 transition-all hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-white/20">
                        Shop Now
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center border border-gray-600 text-gray-300 font-semibold px-8 py-4 rounded-sm text-sm tracking-wider uppercase hover:border-white hover:text-white transition-all">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- Stats Bar --}}
<section class="border-y border-gray-800 bg-[#0a0a0a]">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-800">
            <div class="px-6 py-2 text-center">
                <p class="text-2xl font-bold">{{ $categories->count() }}+</p>
                <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Kategori</p>
            </div>
            <div class="px-6 py-2 text-center">
                <p class="text-2xl font-bold">{{ $featuredProducts->count() }}+</p>
                <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Produk</p>
            </div>
            <div class="px-6 py-2 text-center">
                <p class="text-2xl font-bold">100%</p>
                <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Premium Quality</p>
            </div>
            <div class="px-6 py-2 text-center">
                <p class="text-2xl font-bold">Fast</p>
                <p class="text-gray-500 text-xs uppercase tracking-wider mt-1">Pengiriman</p>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
@if($categories->count() > 0)
<section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-10">
        <p class="text-gray-500 text-xs uppercase tracking-widest font-semibold mb-2">Browse</p>
        <h2 class="text-3xl font-bold">Kategori Produk</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
        @foreach($categories as $cat)
        <div class="border border-gray-800 rounded-lg p-4 text-center hover:border-gray-500 hover:bg-gray-900/30 transition-all cursor-default group">
            <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-sm font-semibold">{{ $cat->name }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $cat->products_count }} item</p>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Featured Products --}}
@if($featuredProducts->count() > 0)
<section class="py-16 bg-[#060606]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-widest font-semibold mb-2">Terbaru</p>
                <h2 class="text-3xl font-bold">Featured Products</h2>
            </div>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('user.shop') }}" class="text-gray-400 hover:text-white text-sm border border-gray-700 hover:border-gray-400 px-4 py-2 rounded-sm transition-all">
                        Lihat Semua →
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="text-gray-400 hover:text-white text-sm border border-gray-700 hover:border-gray-400 px-4 py-2 rounded-sm transition-all">
                    Login untuk Belanja →
                </a>
            @endauth
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProducts as $product)
            <div class="card-hover bg-[#111] border border-gray-800 rounded-xl overflow-hidden group">
                {{-- Product Image --}}
                <div class="relative h-56 bg-gray-900 overflow-hidden">
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
                        <span class="bg-black/70 text-gray-300 text-xs px-2 py-1 rounded-full">{{ $product->category->name }}</span>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="p-5">
                    <h3 class="font-semibold text-sm mb-1 leading-snug">{{ $product->name }}</h3>
                    <p class="text-gray-400 text-xs mb-4 line-clamp-2">{{ $product->description }}</p>
                    <div class="flex items-center justify-between">
                        <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('user.order.create', $product) }}"
                                   class="bg-white text-black text-xs font-semibold px-4 py-2 rounded-sm hover:bg-gray-200 transition-all">
                                    Pesan
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-gray-400 text-xs hover:text-white border border-gray-700 px-4 py-2 rounded-sm transition-all">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
@guest
<section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="border border-gray-800 rounded-2xl p-12 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/20 to-transparent"></div>
        <div class="relative">
            <h2 class="text-4xl font-black mb-4">MULAI BELANJA SEKARANG</h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">Daftar gratis dan nikmati koleksi fashion premium COMR Mini.</p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center bg-white text-black font-bold px-10 py-4 rounded-sm text-sm tracking-wider uppercase hover:bg-gray-200 transition-all hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-white/10">
                Daftar Gratis →
            </a>
        </div>
    </div>
</section>
@endguest

@endsection
