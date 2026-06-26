@extends('layouts.app')

@section('title', 'Pesan - ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-8">
        <a href="{{ route('user.shop') }}" class="hover:text-white transition-colors">Shop</a>
        <span>›</span>
        <span class="text-gray-300">Pesan</span>
    </nav>

    {{-- Product Summary --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl p-5 mb-6 flex items-center space-x-4">
        <div class="w-20 h-20 bg-gray-900 rounded-lg overflow-hidden flex-shrink-0">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-700">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-xs text-gray-500 bg-gray-800 px-2 py-0.5 rounded-full">{{ $product->category->name }}</span>
            <h1 class="font-semibold text-sm mt-1 leading-snug">{{ $product->name }}</h1>
            <p class="font-bold text-lg mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Stok tersedia: <span class="{{ $product->stock <= 5 ? 'text-red-400' : 'text-green-400' }}">{{ $product->stock }}</span></p>
        </div>
    </div>

    {{-- Order Form --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <h2 class="font-bold text-lg mb-6">Form Pemesanan</h2>

        <form method="POST" action="{{ route('user.order.store') }}" id="order-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- Quantity --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Pesanan <span class="text-red-400">*</span></label>
                <div class="flex items-center space-x-3">
                    <button type="button" id="qty-minus"
                        class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors text-xl font-light">
                        −
                    </button>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                        min="1" max="{{ $product->stock }}"
                        class="w-20 text-center bg-black border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('quantity') border-red-500 @enderror">
                    <button type="button" id="qty-plus"
                        class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors text-xl font-light">
                        +
                    </button>
                    <span class="text-gray-500 text-xs">Max: {{ $product->stock }}</span>
                </div>
                @error('quantity') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Shipping Address --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Alamat Pengiriman <span class="text-red-400">*</span></label>
                <textarea name="shipping_address" rows="4"
                    placeholder="Contoh: Jl. Sudirman No. 123, Kelurahan X, Kecamatan Y, Kota Z, 12345"
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                @error('shipping_address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                @if(auth()->user()->address)
                    <p class="text-gray-600 text-xs mt-1">Alamat profil Anda telah diisi otomatis</p>
                @endif
            </div>

            {{-- Order Summary --}}
            <div class="bg-black rounded-lg p-4 mb-6 border border-gray-800">
                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-3">Ringkasan Pesanan</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Harga satuan</span>
                        <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Jumlah</span>
                        <span id="summary-qty">1</span>
                    </div>
                    <div class="border-t border-gray-800 pt-2 flex justify-between font-bold">
                        <span>Total</span>
                        <span id="summary-total">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-white text-black font-bold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                    Konfirmasi Pesanan
                </button>
                <a href="{{ route('user.shop') }}"
                   class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-6 py-3 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>

<script>
    const pricePerUnit = {{ $product->price }};
    const maxStock = {{ $product->stock }};
    const qtyInput = document.getElementById('quantity');
    const summaryQty = document.getElementById('summary-qty');
    const summaryTotal = document.getElementById('summary-total');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function updateSummary() {
        const qty = Math.max(1, Math.min(parseInt(qtyInput.value) || 1, maxStock));
        qtyInput.value = qty;
        summaryQty.textContent = qty;
        summaryTotal.textContent = formatRupiah(qty * pricePerUnit);
    }

    document.getElementById('qty-minus').addEventListener('click', function() {
        const current = parseInt(qtyInput.value) || 1;
        if (current > 1) { qtyInput.value = current - 1; updateSummary(); }
    });

    document.getElementById('qty-plus').addEventListener('click', function() {
        const current = parseInt(qtyInput.value) || 1;
        if (current < maxStock) { qtyInput.value = current + 1; updateSummary(); }
    });

    qtyInput.addEventListener('input', updateSummary);
    updateSummary();
</script>

@endsection
