@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Keranjang Belanja</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $cartItems->count() }} item di keranjang</p>
            </div>
            @if($cartItems->count() > 0)
            <form method="POST" action="{{ route('user.cart.clear') }}" onsubmit="return confirm('Kosongkan semua item di keranjang?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-300 text-sm border border-red-900/50 hover:border-red-700 px-4 py-2 rounded-lg transition-all">
                    Kosongkan Keranjang
                </button>
            </form>
            @endif
        </div>
    </div>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cart Items --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-[#111] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors">
                <div class="flex gap-4">
                    {{-- Product Image --}}
                    <a href="{{ route('user.shop.show', $item->product) }}" class="flex-shrink-0">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                 class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gray-800 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <a href="{{ route('user.shop.show', $item->product) }}" class="font-semibold text-sm hover:text-gray-300 transition-colors line-clamp-2">
                                    {{ $item->product->name }}
                                </a>
                                @if($item->selected_size)
                                    <p class="text-gray-500 text-xs mt-1">Ukuran: <span class="text-gray-300">{{ $item->selected_size }}</span></p>
                                @endif
                                <p class="text-gray-500 text-xs mt-0.5">Stok: {{ $item->product->stock }}</p>
                            </div>

                            {{-- Remove Button --}}
                            <form method="POST" action="{{ route('user.cart.remove', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-600 hover:text-red-400 transition-colors p-1" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- Price & Quantity --}}
                        <div class="flex items-center justify-between mt-3">
                            <p class="font-bold text-white">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>

                            {{-- Quantity Control --}}
                            <form method="POST" action="{{ route('user.cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}"
                                        class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center text-sm transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    −
                                </button>
                                <span class="w-10 text-center text-sm font-semibold">{{ $item->quantity }}</span>
                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                        class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center text-sm transition-colors {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                    +
                                </button>
                            </form>
                        </div>

                        {{-- Subtotal per item --}}
                        <p class="text-gray-400 text-xs mt-2">
                            Subtotal: <span class="text-white font-medium">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-[#111] border border-gray-800 rounded-xl p-6 sticky top-20">
                <h2 class="font-bold text-sm uppercase tracking-wider text-gray-300 mb-4">Ringkasan Belanja</h2>

                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 truncate mr-2">{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="text-gray-300 flex-shrink-0">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="h-px bg-gray-800 my-4"></div>

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-300 font-medium">Total</span>
                    <span class="text-xl font-bold text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <p class="text-gray-600 text-xs mb-4">* Ongkos kirim dihitung saat checkout per produk</p>

                <div class="space-y-2">
                    @foreach($cartItems as $item)
                    <a href="{{ route('user.order.create', $item->product) }}{{ $item->selected_size ? '?size=' . urlencode($item->selected_size) . '&qty=' . $item->quantity : '?qty=' . $item->quantity }}"
                       class="w-full inline-flex items-center justify-center bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesan {{ $item->product->name }}
                    </a>
                    @endforeach
                </div>

                <a href="{{ route('user.shop') }}" class="w-full inline-flex items-center justify-center text-gray-400 hover:text-white border border-gray-700 hover:border-gray-500 py-3 rounded-lg text-sm transition-all mt-3">
                    ← Lanjut Belanja
                </a>
            </div>
        </div>
    </div>

    @else
    {{-- Empty Cart --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl py-20 text-center">
        <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold mb-2">Keranjang Kosong</h2>
        <p class="text-gray-500 text-sm mb-6">Belum ada produk di keranjang belanja Anda</p>
        <a href="{{ route('user.shop') }}"
           class="inline-flex items-center bg-white text-black font-semibold px-6 py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Mulai Belanja
        </a>
    </div>
    @endif

</div>
@endsection
