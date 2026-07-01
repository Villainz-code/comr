@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-8">
        <a href="{{ route('user.shop') }}" class="hover:text-white transition-colors">Shop</a>
        <span>›</span>
        <span class="text-gray-300">{{ $product->name }}</span>
    </nav>

    {{-- Product Detail --}}
    <div class="product-detail-grid">
        {{-- Left: Image --}}
        <div class="product-image-section">
            <div class="product-image-main">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-700">
                        <svg class="w-24 h-24 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="text-sm">No Image</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Info --}}
        <div class="product-info-section">
            {{-- Category Badge --}}
            <div class="mb-4">
                <span class="bg-white/10 text-gray-300 text-xs px-3 py-1.5 rounded-full backdrop-blur-sm border border-gray-700">{{ $product->category->name }}</span>
            </div>

            {{-- Name --}}
            <h1 class="text-2xl md:text-3xl font-black leading-tight mb-3">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="flex items-center gap-4 mb-6">
                <p class="text-3xl font-bold text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-sm {{ $product->stock <= 5 ? 'text-red-400' : 'text-gray-400' }}">
                        Stok: {{ $product->stock }}
                    </span>
                    @if($product->stock <= 5 && $product->stock > 0)
                        <span class="bg-red-900/50 text-red-300 text-xs px-2 py-0.5 rounded-full">Terbatas</span>
                    @endif
                    @if($product->stock <= 0)
                        <span class="bg-red-900/50 text-red-300 text-xs px-2 py-0.5 rounded-full">Habis</span>
                    @endif
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-px bg-gray-800 mb-6"></div>

            {{-- Description --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-3">Deskripsi Produk</h3>
                <p class="text-gray-400 text-sm leading-relaxed whitespace-pre-line">{{ $product->description ?: 'Produk premium dari COMR Mini. Kualitas terbaik dengan bahan pilihan.' }}</p>
            </div>

            {{-- Sizes --}}
            @if($product->sizes && count($product->sizes) > 0)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-3">Ukuran Tersedia</h3>
                <div class="flex flex-wrap gap-2" id="size-selector">
                    @foreach($product->sizes as $size)
                        <button type="button" class="size-btn" data-size="{{ $size }}" onclick="selectSize(this)">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-gray-600 mt-2" id="size-hint">Pilih ukuran sebelum memesan</p>
            </div>
            @endif

            {{-- Product Details Table --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-3">Detail Produk</h3>
                <div class="detail-table">
                    <div class="detail-row">
                        <span class="detail-label">Kategori</span>
                        <span class="detail-value">{{ $product->category->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Stok</span>
                        <span class="detail-value">{{ $product->stock }} unit</span>
                    </div>
                    @if($product->sizes && count($product->sizes) > 0)
                    <div class="detail-row">
                        <span class="detail-label">Ukuran</span>
                        <span class="detail-value">{{ implode(', ', $product->sizes) }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Kondisi</span>
                        <span class="detail-value">Baru</span>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-px bg-gray-800 mb-6"></div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                @if($product->stock > 0)
                <a href="{{ route('user.order.create', $product) }}" id="order-link"
                   class="flex-1 inline-flex items-center justify-center bg-white text-black font-bold py-4 rounded-xl text-sm hover:bg-gray-200 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-white/15">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Pesan Sekarang
                </a>
                @else
                <button disabled class="flex-1 inline-flex items-center justify-center bg-gray-800 text-gray-500 font-bold py-4 rounded-xl text-sm cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Stok Habis
                </button>
                @endif
                <a href="{{ route('user.shop') }}"
                   class="sm:w-auto inline-flex items-center justify-center bg-transparent text-white border border-gray-700 font-medium py-4 px-6 rounded-xl text-sm hover:bg-white/5 hover:border-gray-500 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Shop
                </a>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-xl font-bold mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($relatedProducts as $related)
            <a href="{{ route('user.shop.show', $related) }}" class="block card-hover bg-[#111] border border-gray-800 rounded-xl overflow-hidden group">
                <div class="relative h-44 bg-gray-900 overflow-hidden">
                    @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-xs leading-snug mb-2 line-clamp-2">{{ $related->name }}</h3>
                    <p class="font-bold text-sm">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

<style>
    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
        align-items: start;
    }

    .product-image-main {
        width: 100%;
        aspect-ratio: 1;
        background: #111;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        overflow: hidden;
    }

    .product-info-section {
        padding-top: 8px;
    }

    /* Size Buttons */
    .size-btn {
        min-width: 52px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        background: transparent;
        border: 1px solid #2a2a2a;
        border-radius: 10px;
        color: #d1d5db;
        font-size: 0.8125rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .size-btn:hover {
        border-color: #6b7280;
        background: rgba(255,255,255,0.03);
    }

    .size-btn.active {
        border-color: #ffffff;
        background: rgba(255,255,255,0.08);
        color: #ffffff;
        box-shadow: 0 0 0 1px rgba(255,255,255,0.1);
    }

    /* Detail Table */
    .detail-table {
        background: #0a0a0a;
        border: 1px solid #1a1a1a;
        border-radius: 12px;
        overflow: hidden;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #1a1a1a;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .detail-value {
        font-size: 0.8125rem;
        color: #d1d5db;
        font-weight: 500;
    }

    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    @media (max-width: 768px) {
        .product-detail-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }
</style>

<script>
    let selectedSize = null;

    function selectSize(btn) {
        // Remove active from all
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        // Add active to clicked
        btn.classList.add('active');
        selectedSize = btn.dataset.size;

        // Update order link with size parameter
        const orderLink = document.getElementById('order-link');
        if (orderLink) {
            const baseUrl = "{{ route('user.order.create', $product) }}";
            orderLink.href = baseUrl + '?size=' + encodeURIComponent(selectedSize);
        }

        document.getElementById('size-hint').textContent = 'Ukuran dipilih: ' + selectedSize;
        document.getElementById('size-hint').style.color = '#10b981';
    }
</script>

@endsection
