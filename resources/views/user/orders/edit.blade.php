@extends('layouts.app')

@section('title', 'Edit Pesanan - ' . $product->name)

@section('content')
<div class="checkout-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-8">
            <a href="{{ route('user.orders') }}" class="hover:text-white transition-colors">Pesanan Saya</a>
            <span>›</span>
            <span class="text-gray-300">Edit Pesanan #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
        </nav>

        <form method="POST" action="{{ route('user.order.update', $order) }}" id="checkout-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="payment_channel" id="payment-channel-input" value="{{ old('payment_channel', $order->payment_channel) }}">
            <input type="hidden" name="selected_size" id="selected-size-input" value="{{ old('selected_size', $order->selected_size) }}">

            <div class="checkout-grid">

                {{-- ============================================ --}}
                {{-- LEFT COLUMN: Address & Shipping & Payment    --}}
                {{-- ============================================ --}}
                <div class="checkout-left">

                    {{-- Detail Alamat Section --}}
                    <div class="checkout-section">
                        <h2 class="section-title">Detail Alamat</h2>
                        <p class="section-subtitle">Ubah informasi pengiriman jika diperlukan</p>

                        {{-- Alamat Email --}}
                        <div class="form-group">
                            <div class="input-floating {{ old('email', $order->email) ? 'has-value' : '' }}">
                                <input type="email" id="checkout-email" name="email"
                                    value="{{ old('email', $order->email) }}"
                                    placeholder=" "
                                    class="checkout-input">
                                <label for="checkout-email" class="floating-label">Alamat Email (opsional)</label>
                            </div>
                            <p class="input-hint">Detail pesanan akan dikirim ke email</p>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="form-group">
                            <div class="input-floating {{ old('recipient_name', $order->recipient_name) ? 'has-value' : '' }}">
                                <input type="text" id="checkout-name" name="recipient_name"
                                    value="{{ old('recipient_name', $order->recipient_name) }}"
                                    placeholder=" " required
                                    class="checkout-input">
                                <label for="checkout-name" class="floating-label">Nama Lengkap Penerima</label>
                            </div>
                            @error('recipient_name') <p class="input-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nomor HP --}}
                        <div class="form-group">
                            <div class="input-floating {{ old('phone', $order->phone) ? 'has-value' : '' }}">
                                <input type="tel" id="checkout-phone" name="phone"
                                    value="{{ old('phone', $order->phone) }}"
                                    placeholder=" " required
                                    class="checkout-input">
                                <label for="checkout-phone" class="floating-label">Nomor HP Penerima</label>
                            </div>
                            @error('phone') <p class="input-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Negara --}}
                        <div class="form-group">
                            <div class="input-floating has-value">
                                <select id="checkout-country" name="country" class="checkout-input checkout-select">
                                    <option value="Indonesia" {{ old('country', $order->country) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="Malaysia" {{ old('country', $order->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                    <option value="Singapore" {{ old('country', $order->country) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                    <option value="Thailand" {{ old('country', $order->country) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                </select>
                                <label for="checkout-country" class="floating-label">Negara</label>
                                <div class="select-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Kota dan Kecamatan --}}
                        <div class="form-group">
                            <div class="input-floating {{ old('city', $order->city) ? 'has-value' : '' }}">
                                <input type="text" id="checkout-city" name="city"
                                    value="{{ old('city', $order->city) }}"
                                    placeholder=" "
                                    class="checkout-input input-with-icon">
                                <label for="checkout-city" class="floating-label">Kota dan Kecamatan</label>
                                <div class="input-icon-right">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="M21 21l-4.35-4.35"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Alamat --}}
                        <div class="form-group">
                            <div class="input-floating {{ old('shipping_address', $order->shipping_address) ? 'has-value' : '' }}">
                                <textarea id="checkout-address" name="shipping_address" rows="4"
                                    maxlength="250" placeholder=" " required
                                    class="checkout-input checkout-textarea">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                                <label for="checkout-address" class="floating-label">Detail Alamat</label>
                            </div>
                            <div class="char-counter">
                                <span id="address-count">0</span> / 250
                            </div>
                            @error('shipping_address') <p class="input-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Metode Pengiriman Section --}}
                    <div class="checkout-section">
                        <h2 class="section-title">Metode Pengiriman</h2>

                        <div id="shipping-options" class="shipping-options">
                            <label class="radio-card {{ old('shipping_method', $order->shipping_method) == 'regular' ? 'selected' : '' }}" id="shipping-regular">
                                <input type="radio" name="shipping_method" value="regular" {{ old('shipping_method', $order->shipping_method) == 'regular' ? 'checked' : '' }} class="sr-only">
                                <div class="radio-card-content">
                                    <div class="radio-card-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="1" y="6" width="15" height="12" rx="1"/>
                                            <path d="M16 10h4l3 3v5h-7V10z"/>
                                            <circle cx="5.5" cy="19.5" r="1.5"/>
                                            <circle cx="18.5" cy="19.5" r="1.5"/>
                                        </svg>
                                    </div>
                                    <div class="radio-card-body">
                                        <span class="radio-card-title">Regular</span>
                                        <span class="radio-card-desc">Estimasi 3-5 hari kerja</span>
                                    </div>
                                    <span class="radio-card-price">Rp 15.000</span>
                                </div>
                                <div class="radio-dot"></div>
                            </label>
                            <label class="radio-card {{ old('shipping_method', $order->shipping_method) == 'express' ? 'selected' : '' }}" id="shipping-express">
                                <input type="radio" name="shipping_method" value="express" {{ old('shipping_method', $order->shipping_method) == 'express' ? 'checked' : '' }} class="sr-only">
                                <div class="radio-card-content">
                                    <div class="radio-card-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                        </svg>
                                    </div>
                                    <div class="radio-card-body">
                                        <span class="radio-card-title">Express</span>
                                        <span class="radio-card-desc">Estimasi 1-2 hari kerja</span>
                                    </div>
                                    <span class="radio-card-price">Rp 25.000</span>
                                </div>
                                <div class="radio-dot"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Metode Pembayaran Section --}}
                    <div class="checkout-section">
                        <h2 class="section-title">Metode Pembayaran</h2>
                        <div class="payment-methods">

                            {{-- Transfer Bank --}}
                            <div class="payment-group">
                                <div class="payment-option {{ old('payment_method', $order->payment_method) == 'transfer' ? 'selected expanded' : '' }}" id="payment-transfer" data-method="transfer">
                                    <input type="radio" name="payment_method" value="transfer" {{ old('payment_method', $order->payment_method) == 'transfer' ? 'checked' : '' }} class="sr-only">
                                    <div class="payment-option-content">
                                        <div class="payment-icon bank-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"/>
                                            </svg>
                                        </div>
                                        <div class="payment-info">
                                            <span class="payment-name">Transfer Bank</span>
                                            <span class="payment-selected-channel" id="transfer-selected-label">Pilih bank</span>
                                        </div>
                                        <svg class="payment-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 9l6 6 6-6"/>
                                        </svg>
                                    </div>
                                    <div class="radio-dot"></div>
                                </div>

                                {{-- Bank Sub-Options --}}
                                <div class="payment-sub-options" id="bank-sub-options" style="display:{{ old('payment_method', $order->payment_method) == 'transfer' ? 'block' : 'none' }};">
                                    <div class="sub-option-header">
                                        <span class="sub-option-badge">Virtual Account</span>
                                    </div>
                                    @foreach(['mandiri'=>'Mandiri', 'bri'=>'BRI', 'bni'=>'BNI', 'bca'=>'BCA', 'permata'=>'Permata', 'bsi'=>'Bank Syariah Indonesia'] as $code => $name)
                                    <label class="sub-option {{ old('payment_channel', $order->payment_channel) == $code ? 'selected' : '' }}" data-channel="{{ $code }}">
                                        <input type="radio" name="bank_channel" value="{{ $code }}" {{ old('payment_channel', $order->payment_channel) == $code ? 'checked' : '' }} class="sr-only">
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">{{ $name }}</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- E-Wallet --}}
                            <div class="payment-group">
                                <div class="payment-option {{ old('payment_method', $order->payment_method) == 'ewallet' ? 'selected expanded' : '' }}" id="payment-ewallet" data-method="ewallet">
                                    <input type="radio" name="payment_method" value="ewallet" {{ old('payment_method', $order->payment_method) == 'ewallet' ? 'checked' : '' }} class="sr-only">
                                    <div class="payment-option-content">
                                        <div class="payment-icon ewallet-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                                <path d="M16 12a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <div class="payment-info">
                                            <span class="payment-name">E-Wallet</span>
                                            <span class="payment-selected-channel" id="ewallet-selected-label">Pilih e-wallet</span>
                                        </div>
                                        <svg class="payment-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 9l6 6 6-6"/>
                                        </svg>
                                    </div>
                                    <div class="radio-dot"></div>
                                </div>

                                {{-- E-Wallet Sub-Options --}}
                                <div class="payment-sub-options" id="ewallet-sub-options" style="display:{{ old('payment_method', $order->payment_method) == 'ewallet' ? 'block' : 'none' }};">
                                    @foreach(['gopay'=>'GoPay', 'ovo'=>'OVO', 'dana'=>'DANA', 'shopeepay'=>'ShopeePay', 'linkaja'=>'LinkAja'] as $code => $name)
                                    <label class="sub-option {{ old('payment_channel', $order->payment_channel) == $code ? 'selected' : '' }}" data-channel="{{ $code }}">
                                        <input type="radio" name="ewallet_channel" value="{{ $code }}" {{ old('payment_channel', $order->payment_channel) == $code ? 'checked' : '' }} class="sr-only">
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">{{ $name }}</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- COD --}}
                            <div class="payment-group">
                                <div class="payment-option {{ old('payment_method', $order->payment_method) == 'cod' ? 'selected' : '' }}" id="payment-cod" data-method="cod">
                                    <input type="radio" name="payment_method" value="cod" {{ old('payment_method', $order->payment_method) == 'cod' ? 'checked' : '' }} class="sr-only">
                                    <div class="payment-option-content">
                                        <div class="payment-icon cod-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                                <circle cx="12" cy="12" r="3"/>
                                                <path d="M2 10h2M20 10h2M2 14h2M20 14h2"/>
                                            </svg>
                                        </div>
                                        <div class="payment-info">
                                            <span class="payment-name">COD (Bayar di Tempat)</span>
                                            <span class="payment-selected-channel" style="color: #6b7280;">Bayar saat barang diterima</span>
                                        </div>
                                    </div>
                                    <div class="radio-dot"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- RIGHT COLUMN: Order Summary (Sticky)         --}}
                {{-- ============================================ --}}
                <div class="checkout-right">
                    <div class="order-summary-sticky">

                        {{-- Product Card --}}
                        <div class="order-product-card">
                            <div class="product-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="product-thumb-placeholder">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="product-details">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-variant">{{ $product->category->name }}</p>
                            </div>
                            <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>

                        {{-- Sizes (if applicable) --}}
                        @if($product->sizes && count($product->sizes) > 0)
                        <div class="mb-4">
                            <span class="text-xs text-gray-400 mb-2 block">Pilih Ukuran</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $size)
                                    <button type="button" class="size-btn {{ old('selected_size', $order->selected_size) == $size ? 'active' : '' }}" data-size="{{ $size }}" onclick="selectSize(this)">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Quantity Selector --}}
                        <div class="quantity-selector">
                            <span class="quantity-label">Jumlah Pesanan</span>
                            <div class="quantity-controls">
                                <button type="button" id="qty-minus" class="qty-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M5 12h14"/>
                                    </svg>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $order->quantity) }}"
                                    min="1" max="{{ $product->stock + $order->quantity }}" class="qty-input" readonly>
                                <button type="button" id="qty-plus" class="qty-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="stock-info">Stok Tersedia: {{ $product->stock + $order->quantity }}</span>
                        </div>
                        @error('quantity') <p class="input-error" style="margin: -8px 0 12px">{{ $message }}</p> @enderror

                        {{-- Shipping Message --}}
                        <div class="checkout-addon" id="shipping-message-toggle">
                            <div class="addon-left">
                                <span class="addon-text">Tinggalkan pesan pengiriman (opsional)</span>
                            </div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </div>
                        <div class="shipping-message-area {{ old('shipping_note', $order->shipping_note) ? '' : 'hidden' }}" id="shipping-message-area">
                            <textarea name="shipping_note" id="shipping-note" rows="2"
                                placeholder="Tulis catatan untuk kurir..."
                                class="shipping-message-input">{{ old('shipping_note', $order->shipping_note) }}</textarea>
                        </div>

                        {{-- Divider --}}
                        <div class="summary-divider"></div>

                        {{-- Price Breakdown --}}
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span class="price-label">Subtotal · <span id="summary-qty-text">{{ $order->quantity }}</span> barang</span>
                                <span class="price-value" id="summary-subtotal">Rp {{ number_format($product->price * $order->quantity, 0, ',', '.') }}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Pengiriman</span>
                                <span class="price-value" id="shipping-cost">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="total-section">
                            <span class="total-label">Total Pembayaran</span>
                            <span class="total-value" id="summary-total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="order-submit-btn" class="order-button">
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('user.orders') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-white transition-colors">
                            Batal Edit
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* ========== REUSE CSS FROM CREATE.BLADE.PHP ========== */
    .checkout-page { min-height: 100vh; padding-bottom: 80px; }
    .checkout-grid { display: grid; grid-template-columns: 1fr 420px; gap: 40px; align-items: start; }
    .checkout-left { display: flex; flex-direction: column; gap: 32px; }
    .checkout-section { background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 16px; padding: 32px; }
    .section-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 4px; letter-spacing: -0.02em; }
    .section-subtitle { font-size: 0.8125rem; color: #6b7280; margin-bottom: 24px; }
    .form-group { margin-bottom: 16px; }
    .input-floating { position: relative; }
    .checkout-input { width: 100%; background: transparent; border: 1px solid #2a2a2a; border-radius: 12px; padding: 20px 16px 8px; color: #ffffff; font-size: 0.875rem; font-family: 'Inter', sans-serif; transition: border-color 0.2s ease, box-shadow 0.2s ease; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    .checkout-input:focus { border-color: #ffffff; box-shadow: 0 0 0 1px rgba(255,255,255,0.1); }
    .checkout-textarea { resize: none; min-height: 100px; }
    .floating-label { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem; pointer-events: none; transition: all 0.2s ease; }
    .checkout-textarea ~ .floating-label { top: 20px; transform: translateY(0); }
    .checkout-input:focus ~ .floating-label, .checkout-input:not(:placeholder-shown) ~ .floating-label, .has-value .floating-label { top: 10px; transform: translateY(0); font-size: 0.6875rem; color: #9ca3af; }
    .checkout-textarea:focus ~ .floating-label, .checkout-textarea:not(:placeholder-shown) ~ .floating-label { top: 8px; font-size: 0.6875rem; color: #9ca3af; }
    .input-hint { font-size: 0.6875rem; color: #4b5563; margin-top: 6px; padding-left: 4px; }
    .input-error { font-size: 0.75rem; color: #f87171; margin-top: 4px; padding-left: 4px; }
    .checkout-select { cursor: pointer; padding-right: 40px; }
    .checkout-select option { background: #111; color: #fff; }
    .select-arrow { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none; }
    .input-with-icon { padding-right: 48px; }
    .input-icon-right { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none; }
    .char-counter { text-align: right; font-size: 0.6875rem; color: #4b5563; margin-top: 4px; }
    
    .radio-card { border: 1px solid #2a2a2a; border-radius: 12px; padding: 16px 56px 16px 20px; cursor: pointer; transition: all 0.2s ease; position: relative; display: block; margin-bottom: 8px; }
    .radio-card:hover { border-color: #3a3a3a; background: rgba(255,255,255,0.01); }
    .radio-card.selected { border-color: #ffffff; background: rgba(255,255,255,0.02); }
    .radio-card-content { display: flex; align-items: center; gap: 14px; }
    .radio-card-icon { color: #9ca3af; flex-shrink: 0; }
    .radio-card-body { flex: 1; display: flex; flex-direction: column; gap: 2px; }
    .radio-card-title { font-size: 0.875rem; font-weight: 600; }
    .radio-card-desc { font-size: 0.75rem; color: #6b7280; }
    .radio-card-price { font-size: 0.875rem; font-weight: 600; color: #9ca3af; white-space: nowrap; }
    .radio-dot { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; border-radius: 50%; border: 2px solid #3a3a3a; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .radio-dot::after { content: ''; width: 0; height: 0; background: #ffffff; border-radius: 50%; transition: all 0.2s ease; }
    .selected > .radio-dot, .radio-card.selected .radio-dot { border-color: #ffffff; }
    .selected > .radio-dot::after, .radio-card.selected .radio-dot::after { width: 10px; height: 10px; }
    
    .radio-dot-sm { width: 18px; height: 18px; min-width: 18px; border-radius: 50%; border: 2px solid #3a3a3a; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-left: auto; }
    .radio-dot-sm::after { content: ''; width: 0; height: 0; background: #ffffff; border-radius: 50%; transition: all 0.2s ease; }
    .sub-option.selected .radio-dot-sm { border-color: #ffffff; }
    .sub-option.selected .radio-dot-sm::after { width: 8px; height: 8px; }
    
    .payment-methods { display: flex; flex-direction: column; gap: 8px; }
    .payment-group { display: flex; flex-direction: column; }
    .payment-option { border: 1px solid #2a2a2a; border-radius: 12px; padding: 16px 56px 16px 20px; cursor: pointer; transition: all 0.2s ease; position: relative; }
    .payment-option:hover { border-color: #3a3a3a; }
    .payment-option.selected { border-color: #ffffff; background: rgba(255,255,255,0.02); }
    .payment-option-content { display: flex; align-items: center; gap: 14px; }
    .payment-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .bank-icon { background: linear-gradient(135deg, #1a365d, #2563eb); color: #fff; }
    .ewallet-icon { background: linear-gradient(135deg, #065f46, #10b981); color: #fff; }
    .cod-icon { background: linear-gradient(135deg, #78350f, #f59e0b); color: #fff; }
    .payment-info { flex: 1; display: flex; flex-direction: column; gap: 2px; }
    .payment-name { font-size: 0.875rem; font-weight: 500; }
    .payment-selected-channel { font-size: 0.6875rem; color: #9ca3af; }
    .payment-chevron { color: #6b7280; transition: transform 0.3s ease; flex-shrink: 0; }
    .payment-option.expanded .payment-chevron { transform: rotate(180deg); }
    
    .payment-sub-options { border: 1px solid #1a1a1a; border-top: none; border-radius: 0 0 12px 12px; background: #0d0d0d; padding: 4px 8px 8px; margin-top: -8px; animation: slideDown 0.25s ease; overflow: hidden; }
    .sub-option-header { padding: 12px 12px 8px; }
    .sub-option-badge { display: inline-block; padding: 6px 16px; border: 1px solid #2a2a2a; border-radius: 8px; font-size: 0.75rem; font-weight: 600; color: #d1d5db; background: transparent; }
    .sub-option { display: flex; align-items: center; gap: 14px; padding: 14px 12px; border-radius: 10px; cursor: pointer; transition: all 0.15s ease; }
    .sub-option:hover { background: rgba(255,255,255,0.03); }
    .sub-option + .sub-option { border-top: 1px solid #1a1a1a; }
    .sub-option-info { flex: 1; display: flex; flex-direction: column; gap: 2px; }
    .sub-option-name { font-size: 0.8125rem; font-weight: 600; }
    
    .checkout-right { position: relative; }
    .order-summary-sticky { position: sticky; top: 80px; background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 16px; padding: 24px; }
    
    .order-product-card { display: flex; align-items: flex-start; gap: 14px; padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid #1a1a1a; }
    .product-thumb { width: 52px; height: 52px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: #1a1a1a; }
    .product-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .product-thumb-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #4b5563; }
    .product-details { flex: 1; min-width: 0; }
    .product-name { font-size: 0.8125rem; font-weight: 600; line-height: 1.4; margin-bottom: 2px; }
    .product-variant { font-size: 0.6875rem; color: #6b7280; }
    .product-price { font-size: 0.875rem; font-weight: 600; white-space: nowrap; }
    
    .size-btn { min-width: 44px; height: 36px; display: inline-flex; align-items: center; justify-content: center; padding: 0 12px; background: transparent; border: 1px solid #2a2a2a; border-radius: 8px; color: #d1d5db; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .size-btn:hover { border-color: #6b7280; background: rgba(255,255,255,0.03); }
    .size-btn.active { border-color: #ffffff; background: rgba(255,255,255,0.08); color: #ffffff; }

    .quantity-selector { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; margin-bottom: 12px; border-bottom: 1px solid #1a1a1a; }
    .quantity-label { font-size: 0.8125rem; color: #9ca3af; }
    .quantity-controls { display: flex; align-items: center; gap: 0; border: 1px solid #2a2a2a; border-radius: 8px; overflow: hidden; }
    .qty-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; color: #9ca3af; cursor: pointer; transition: all 0.15s; }
    .qty-btn:hover { background: #1a1a1a; color: #fff; }
    .qty-input { width: 40px; text-align: center; background: transparent; border: none; border-left: 1px solid #2a2a2a; border-right: 1px solid #2a2a2a; color: #ffffff; font-size: 0.8125rem; height: 32px; outline: none; }
    .stock-info { font-size: 0.6875rem; color: #4b5563; }
    
    .checkout-addon { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border: 1px solid #1a1a1a; border-radius: 10px; cursor: pointer; transition: all 0.2s; margin-bottom: 8px; }
    .checkout-addon:hover { border-color: #2a2a2a; background: rgba(255,255,255,0.01); }
    .addon-left { display: flex; align-items: center; gap: 10px; }
    .addon-text { font-size: 0.8125rem; color: #9ca3af; }
    .shipping-message-area { padding: 0 4px; margin-bottom: 8px; animation: slideDown 0.2s ease; }
    .shipping-message-input { width: 100%; background: #111; border: 1px solid #2a2a2a; border-radius: 10px; padding: 12px 14px; color: #fff; font-size: 0.8125rem; resize: none; outline: none; transition: border-color 0.2s; }
    .shipping-message-input:focus { border-color: #fff; }
    
    .summary-divider { height: 1px; background: #1a1a1a; margin: 16px 0; }
    .price-breakdown { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }
    .price-row { display: flex; justify-content: space-between; align-items: center; }
    .price-label { font-size: 0.8125rem; color: #9ca3af; }
    .price-value { font-size: 0.8125rem; font-weight: 500; }
    .total-section { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-top: 1px solid #1a1a1a; margin-bottom: 16px; }
    .total-label { font-size: 0.875rem; font-weight: 600; }
    .total-value { font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; }
    
    .order-button { width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background: #ffffff; color: #000000; border: none; border-radius: 14px; padding: 16px 24px; font-size: 0.9375rem; font-weight: 700; cursor: pointer; transition: all 0.25s ease; }
    .order-button:hover { background: #e5e5e5; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(255,255,255,0.15); }
    
    @keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .hidden { display: none !important; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
</style>

<script>
    // ========== SIZE LOGIC ==========
    function selectSize(btn) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('selected-size-input').value = btn.dataset.size;
    }

    // ========== SHIPPING COSTS ==========
    const SHIPPING_COSTS = {
        regular: 15000,
        express: 25000
    };

    // ========== PRICE & QUANTITY LOGIC ==========
    const pricePerUnit = {{ $product->price }};
    const maxStock = {{ $product->stock + $order->quantity }};
    const qtyInput = document.getElementById('quantity');
    const summaryQtyText = document.getElementById('summary-qty-text');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const shippingCostEl = document.getElementById('shipping-cost');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function getSelectedShippingCost() {
        const selected = document.querySelector('input[name="shipping_method"]:checked');
        if (selected) {
            return SHIPPING_COSTS[selected.value] || 0;
        }
        return SHIPPING_COSTS.regular;
    }

    function updateSummary() {
        const qty = Math.max(1, Math.min(parseInt(qtyInput.value) || 1, maxStock));
        qtyInput.value = qty;
        summaryQtyText.textContent = qty;

        const subtotal = qty * pricePerUnit;
        const shippingCost = getSelectedShippingCost();
        const total = subtotal + shippingCost;

        summarySubtotal.textContent = formatRupiah(subtotal);
        shippingCostEl.textContent = formatRupiah(shippingCost);
        summaryTotal.textContent = formatRupiah(total);
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

    // ========== CHARACTER COUNTER ==========
    const addressField = document.getElementById('checkout-address');
    const charCount = document.getElementById('address-count');

    function updateCharCount() {
        charCount.textContent = addressField.value.length;
    }
    addressField.addEventListener('input', updateCharCount);
    updateCharCount();

    // ========== SHIPPING MESSAGE TOGGLE ==========
    document.getElementById('shipping-message-toggle').addEventListener('click', function() {
        const area = document.getElementById('shipping-message-area');
        area.classList.toggle('hidden');
    });

    // ========== SHIPPING METHOD SELECTION ==========
    document.querySelectorAll('.radio-card').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.radio-card').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            updateSummary();
        });
    });

    // ========== PAYMENT METHOD SELECTION ==========
    const paymentOptions = document.querySelectorAll('.payment-option');
    const bankSubOptions = document.getElementById('bank-sub-options');
    const ewalletSubOptions = document.getElementById('ewallet-sub-options');
    const paymentChannelInput = document.getElementById('payment-channel-input');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            const method = this.dataset.method;

            paymentOptions.forEach(o => {
                o.classList.remove('selected', 'expanded');
            });

            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;

            if (method === 'transfer') {
                bankSubOptions.style.display = 'block';
                ewalletSubOptions.style.display = 'none';
                this.classList.add('expanded');
                const checkedBank = document.querySelector('input[name="bank_channel"]:checked');
                if (checkedBank) paymentChannelInput.value = checkedBank.value;
            } else if (method === 'ewallet') {
                bankSubOptions.style.display = 'none';
                ewalletSubOptions.style.display = 'block';
                this.classList.add('expanded');
                const checkedEwallet = document.querySelector('input[name="ewallet_channel"]:checked');
                if (checkedEwallet) paymentChannelInput.value = checkedEwallet.value;
            } else {
                bankSubOptions.style.display = 'none';
                ewalletSubOptions.style.display = 'none';
                paymentChannelInput.value = 'cod';
            }
        });
    });

    // ========== BANK SUB-OPTION SELECTION ==========
    document.querySelectorAll('#bank-sub-options .sub-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('#bank-sub-options .sub-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            paymentChannelInput.value = this.dataset.channel;
        });
    });

    // ========== E-WALLET SUB-OPTION SELECTION ==========
    document.querySelectorAll('#ewallet-sub-options .sub-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('#ewallet-sub-options .sub-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            paymentChannelInput.value = this.dataset.channel;
        });
    });
</script>
@endsection
