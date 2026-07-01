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

            {{-- Select All --}}
            <div class="bg-[#111] border border-gray-800 rounded-xl px-5 py-3 flex items-center gap-3">
                <label class="relative flex items-center cursor-pointer group">
                    <input type="checkbox" id="select-all" class="peer sr-only" checked>
                    <div class="w-5 h-5 border-2 border-gray-600 rounded-md flex items-center justify-center transition-all
                                peer-checked:border-white peer-checked:bg-white group-hover:border-gray-400">
                        <svg class="w-3 h-3 text-black hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </label>
                <span class="text-sm font-medium text-gray-300">Pilih Semua (<span id="selected-count">{{ $cartItems->count() }}</span>/{{ $cartItems->count() }})</span>
            </div>

            @foreach($cartItems as $item)
            <div class="bg-[#111] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors cart-item-card" data-cart-id="{{ $item->id }}" data-price="{{ $item->product->price }}" data-qty="{{ $item->quantity }}" data-name="{{ $item->product->name }}" data-product-id="{{ $item->product->id }}" data-size="{{ $item->selected_size ?? '' }}" data-order-url="{{ route('user.order.create', $item->product) }}">
                <div class="flex gap-4">

                    {{-- Checkbox --}}
                    <div class="flex items-start pt-1">
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="checkbox" class="cart-item-check peer sr-only" data-cart-id="{{ $item->id }}" checked>
                            <div class="w-5 h-5 border-2 border-gray-600 rounded-md flex items-center justify-center transition-all
                                        peer-checked:border-white peer-checked:bg-white group-hover:border-gray-400">
                                <svg class="w-3 h-3 text-black hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </label>
                    </div>

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

                <div id="summary-items" class="space-y-3 mb-4">
                    {{-- Populated by JS --}}
                </div>

                <div id="summary-empty" class="text-center py-4 hidden">
                    <p class="text-gray-500 text-sm">Pilih produk untuk melihat ringkasan</p>
                </div>

                <div class="h-px bg-gray-800 my-4"></div>

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-300 font-medium">Total (<span id="summary-selected-count">0</span> produk)</span>
                    <span class="text-xl font-bold text-white" id="summary-total">Rp 0</span>
                </div>

                <p class="text-gray-600 text-xs mb-4">* Ongkos kirim dihitung saat checkout per produk</p>

                {{-- Bulk Checkout Button --}}
                <form method="GET" action="{{ route('user.order.createBulk') }}" id="bulk-checkout-form" class="hidden mb-3">
                    <input type="hidden" name="cart_ids" id="bulk-cart-ids" value="">
                    <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold py-3.5 rounded-lg text-sm hover:from-emerald-400 hover:to-green-500 transition-all hover:-translate-y-0.5 shadow-lg shadow-green-900/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Checkout Semua Produk Terpilih
                    </button>
                </form>

                <div id="summary-buttons" class="space-y-2">
                    {{-- Populated by JS --}}
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

@if($cartItems->count() > 0)
<style>
    .cart-item-card {
        transition: all 0.2s ease;
    }
    .cart-item-card.unselected {
        opacity: 0.5;
        border-color: #1f2937 !important;
    }
    .cart-item-card.unselected:hover {
        opacity: 0.7;
    }
    /* Fix checkbox rendering */
    .peer:checked ~ div svg { display: block !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.cart-item-check');
    const summaryItems = document.getElementById('summary-items');
    const summaryEmpty = document.getElementById('summary-empty');
    const summaryTotal = document.getElementById('summary-total');
    const summaryButtons = document.getElementById('summary-buttons');
    const summarySelectedCount = document.getElementById('summary-selected-count');
    const selectedCountLabel = document.getElementById('selected-count');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function updateSummary() {
        let total = 0;
        let selectedCount = 0;
        let itemsHtml = '';
        let buttonsHtml = '';
        let selectedCartIds = [];

        itemCheckboxes.forEach(checkbox => {
            const card = checkbox.closest('.cart-item-card');
            const isChecked = checkbox.checked;

            if (isChecked) {
                card.classList.remove('unselected');
                selectedCount++;
                selectedCartIds.push(card.dataset.cartId);

                const price = parseInt(card.dataset.price);
                const qty = parseInt(card.dataset.qty);
                const name = card.dataset.name;
                const size = card.dataset.size;
                const orderUrl = card.dataset.orderUrl;
                const subtotal = price * qty;
                total += subtotal;

                // Build query params
                let params = '?qty=' + qty;
                if (size) params = '?size=' + encodeURIComponent(size) + '&qty=' + qty;

                itemsHtml += `
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 truncate mr-2">${name} × ${qty}</span>
                        <span class="text-gray-300 flex-shrink-0">${formatRupiah(subtotal)}</span>
                    </div>
                `;

                buttonsHtml += `
                    <a href="${orderUrl}${params}"
                       class="w-full inline-flex items-center justify-center bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesan ${name}
                    </a>
                `;
            } else {
                card.classList.add('unselected');
            }
        });

        // Update summary display
        summaryItems.innerHTML = itemsHtml;
        summaryTotal.textContent = formatRupiah(total);
        summarySelectedCount.textContent = selectedCount;
        selectedCountLabel.textContent = selectedCount;

        if (selectedCount === 0) {
            summaryEmpty.classList.remove('hidden');
            summaryItems.classList.add('hidden');
        } else {
            summaryEmpty.classList.add('hidden');
            summaryItems.classList.remove('hidden');
        }

        // Bulk checkout button: show when 2+ selected
        const bulkForm = document.getElementById('bulk-checkout-form');
        const bulkCartIds = document.getElementById('bulk-cart-ids');
        if (selectedCount > 1) {
            bulkForm.classList.remove('hidden');
            bulkCartIds.value = selectedCartIds.join(',');
            summaryButtons.innerHTML = ''; // Hide individual buttons
        } else {
            bulkForm.classList.add('hidden');
            bulkCartIds.value = '';
            summaryButtons.innerHTML = buttonsHtml; // Show individual button
        }

        // Update Select All checkbox state
        if (selectedCount === itemCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selectedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Select All handler
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSummary();
    });

    // Individual checkbox handlers
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    // Initialize on load
    updateSummary();
});
</script>
@endif
@endsection
