@extends('layouts.app')

@section('title', 'Checkout - ' . $product->name)

@section('content')
<div class="checkout-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-8">
            <a href="{{ route('user.shop') }}" class="hover:text-white transition-colors">Shop</a>
            <span>›</span>
            <span class="text-gray-300">Checkout</span>
        </nav>

        <form method="POST" action="{{ route('user.order.store') }}" id="checkout-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="payment_channel" id="payment-channel-input" value="">

            <div class="checkout-grid">

                {{-- ============================================ --}}
                {{-- LEFT COLUMN: Address & Shipping & Payment    --}}
                {{-- ============================================ --}}
                <div class="checkout-left">

                    {{-- Detail Alamat Section --}}
                    <div class="checkout-section">
                        <h2 class="section-title">Detail Alamat</h2>
                        <p class="section-subtitle">
                            Apakah Anda memiliki akun?
                            <a href="{{ route('user.profile') }}" class="account-link">Profil Saya</a>
                        </p>

                        {{-- Alamat Email --}}
                        <div class="form-group">
                            <div class="input-floating">
                                <input type="email" id="checkout-email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    placeholder=" "
                                    class="checkout-input">
                                <label for="checkout-email" class="floating-label">Alamat Email (opsional)</label>
                            </div>
                            <p class="input-hint">Detail pesanan akan dikirim ke email</p>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="form-group">
                            <div class="input-floating">
                                <input type="text" id="checkout-name" name="recipient_name"
                                    value="{{ old('recipient_name', auth()->user()->name) }}"
                                    placeholder=" " required
                                    class="checkout-input">
                                <label for="checkout-name" class="floating-label">Nama Lengkap Penerima</label>
                            </div>
                            @error('recipient_name') <p class="input-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nomor HP --}}
                        <div class="form-group">
                            <div class="input-floating">
                                <input type="tel" id="checkout-phone" name="phone"
                                    value="{{ old('phone', auth()->user()->phone) }}"
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
                                    <option value="Indonesia" selected>Indonesia</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Thailand">Thailand</option>
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
                            <div class="input-floating">
                                <input type="text" id="checkout-city" name="city"
                                    value="{{ old('city') }}"
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
                            <div class="input-floating">
                                <textarea id="checkout-address" name="shipping_address" rows="4"
                                    maxlength="250" placeholder=" " required
                                    class="checkout-input checkout-textarea">{{ old('shipping_address', auth()->user()->address) }}</textarea>
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
                        <div class="shipping-notice">
                            <p>Lengkapi rincian alamat untuk melihat metode pengiriman yang tersedia.</p>
                        </div>

                        {{-- Shipping options (appear after address) --}}
                        <div id="shipping-options" class="shipping-options hidden">
                            <label class="radio-card selected" id="shipping-regular">
                                <input type="radio" name="shipping_method" value="regular" checked class="sr-only">
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
                            <label class="radio-card" id="shipping-express">
                                <input type="radio" name="shipping_method" value="express" class="sr-only">
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
                                <div class="payment-option selected" id="payment-transfer" data-method="transfer">
                                    <input type="radio" name="payment_method" value="transfer" checked class="sr-only">
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
                                <div class="payment-sub-options" id="bank-sub-options">
                                    <div class="sub-option-header">
                                        <span class="sub-option-badge">Virtual Account</span>
                                    </div>
                                    <label class="sub-option selected" data-channel="mandiri">
                                        <input type="radio" name="bank_channel" value="mandiri" checked class="sr-only">
                                        <div class="sub-option-icon" style="background: #003d79;">
                                            <span style="font-size: 8px; font-weight: 800; color: #f7c500; letter-spacing: -0.5px;">mandiri</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">Mandiri</span>
                                            <span class="sub-option-desc">VA • Hanya menerima dari Mandiri</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="bri">
                                        <input type="radio" name="bank_channel" value="bri" class="sr-only">
                                        <div class="sub-option-icon" style="background: #00529c;">
                                            <span style="font-size: 8px; font-weight: 800; color: #fff; letter-spacing: -0.3px;">BRI</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">BRI</span>
                                            <span class="sub-option-desc">VA • Hanya menerima dari BRI</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="bni">
                                        <input type="radio" name="bank_channel" value="bni" class="sr-only">
                                        <div class="sub-option-icon" style="background: #e8590c;">
                                            <span style="font-size: 8px; font-weight: 800; color: #fff; letter-spacing: -0.3px;">BNI</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">BNI</span>
                                            <span class="sub-option-desc">VA • Menerima dari semua bank</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="bca">
                                        <input type="radio" name="bank_channel" value="bca" class="sr-only">
                                        <div class="sub-option-icon" style="background: #003b71;">
                                            <span style="font-size: 8px; font-weight: 800; color: #fff; letter-spacing: -0.3px;">BCA</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">BCA</span>
                                            <span class="sub-option-desc">VA • Hanya menerima dari BCA</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="permata">
                                        <input type="radio" name="bank_channel" value="permata" class="sr-only">
                                        <div class="sub-option-icon" style="background: linear-gradient(135deg, #1a5c3a, #2ecc71);">
                                            <span style="font-size: 7px; font-weight: 700; color: #fff;">Permata</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">Permata</span>
                                            <span class="sub-option-desc">VA • Menerima dari semua bank</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="bsi">
                                        <input type="radio" name="bank_channel" value="bsi" class="sr-only">
                                        <div class="sub-option-icon" style="background: linear-gradient(135deg, #004d40, #00796b);">
                                            <span style="font-size: 7px; font-weight: 800; color: #ffd700;">BSI</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">Bank Syariah Indonesia</span>
                                            <span class="sub-option-desc">VA • Menerima dari semua bank</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                </div>
                            </div>

                            {{-- E-Wallet --}}
                            <div class="payment-group">
                                <div class="payment-option" id="payment-ewallet" data-method="ewallet">
                                    <input type="radio" name="payment_method" value="ewallet" class="sr-only">
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
                                <div class="payment-sub-options" id="ewallet-sub-options" style="display:none;">
                                    <label class="sub-option" data-channel="gopay">
                                        <input type="radio" name="ewallet_channel" value="gopay" class="sr-only">
                                        <div class="sub-option-icon" style="background: #00aed6;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                                                <path d="M8 12l3 3 5-5" stroke="#fff" stroke-width="2"/>
                                            </svg>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">GoPay</span>
                                            <span class="sub-option-desc">Bayar langsung via GoPay</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="ovo">
                                        <input type="radio" name="ewallet_channel" value="ovo" class="sr-only">
                                        <div class="sub-option-icon" style="background: #4c3494;">
                                            <span style="font-size: 8px; font-weight: 800; color: #fff;">OVO</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">OVO</span>
                                            <span class="sub-option-desc">Bayar langsung via OVO</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="dana">
                                        <input type="radio" name="ewallet_channel" value="dana" class="sr-only">
                                        <div class="sub-option-icon" style="background: #108ee9;">
                                            <span style="font-size: 7px; font-weight: 800; color: #fff;">DANA</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">DANA</span>
                                            <span class="sub-option-desc">Bayar langsung via DANA</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="shopeepay">
                                        <input type="radio" name="ewallet_channel" value="shopeepay" class="sr-only">
                                        <div class="sub-option-icon" style="background: #ee4d2d;">
                                            <span style="font-size: 6px; font-weight: 700; color: #fff;">SPay</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">ShopeePay</span>
                                            <span class="sub-option-desc">Bayar langsung via ShopeePay</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                    <label class="sub-option" data-channel="linkaja">
                                        <input type="radio" name="ewallet_channel" value="linkaja" class="sr-only">
                                        <div class="sub-option-icon" style="background: #e3342f;">
                                            <span style="font-size: 6px; font-weight: 700; color: #fff;">LinkAja</span>
                                        </div>
                                        <div class="sub-option-info">
                                            <span class="sub-option-name">LinkAja</span>
                                            <span class="sub-option-desc">Bayar langsung via LinkAja</span>
                                        </div>
                                        <div class="radio-dot-sm"></div>
                                    </label>
                                </div>
                            </div>

                            {{-- COD --}}
                            <div class="payment-group">
                                <div class="payment-option" id="payment-cod" data-method="cod">
                                    <input type="radio" name="payment_method" value="cod" class="sr-only">
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
                                <p class="product-quantity">Jumlah: <span id="display-qty">1</span></p>
                            </div>
                            <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>

                        {{-- Quantity Selector --}}
                        <div class="quantity-selector">
                            <span class="quantity-label">Jumlah Pesanan</span>
                            <div class="quantity-controls">
                                <button type="button" id="qty-minus" class="qty-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M5 12h14"/>
                                    </svg>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                                    min="1" max="{{ $product->stock }}" class="qty-input" readonly>
                                <button type="button" id="qty-plus" class="qty-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="stock-info">Stok: {{ $product->stock }}</span>
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
                        <div class="shipping-message-area hidden" id="shipping-message-area">
                            <textarea name="shipping_note" id="shipping-note" rows="2"
                                placeholder="Tulis catatan untuk kurir..."
                                class="shipping-message-input"></textarea>
                        </div>

                        {{-- Voucher --}}
                        <div class="checkout-addon voucher-addon" id="voucher-toggle">
                            <div class="addon-left">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="addon-text">Voucher</span>
                            </div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </div>
                        <div class="voucher-input-area hidden" id="voucher-area">
                            <input type="text" name="voucher_code" placeholder="Masukkan kode voucher"
                                class="voucher-input">
                            <button type="button" class="voucher-apply-btn">Terapkan</button>
                        </div>

                        {{-- Divider --}}
                        <div class="summary-divider"></div>

                        {{-- Price Breakdown --}}
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span class="price-label">Subtotal · <span id="summary-qty-text">1</span> barang</span>
                                <span class="price-value" id="summary-subtotal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Diskon Produk</span>
                                <span class="price-value discount">-</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Pengiriman</span>
                                <span class="price-value" id="shipping-cost">-</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="total-section">
                            <span class="total-label">Total Pembayaran</span>
                            <span class="total-value" id="summary-total">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        {{-- Security Badge --}}
                        <div class="security-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <span>Transaksi Aman | Pembayaran telah terenkripsi.</span>
                        </div>

                        {{-- Import Notice --}}
                        <div class="import-notice">
                            <p>Bea masuk atau pajak impor mungkin dikenakan tergantung negara tujuan pengiriman.</p>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="order-submit-btn" class="order-button">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Order Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* ========== CHECKOUT PAGE LAYOUT ========== */
    .checkout-page {
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 40px;
        align-items: start;
    }

    /* ========== LEFT COLUMN ========== */
    .checkout-left {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .checkout-section {
        background: #0a0a0a;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        padding: 32px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .section-subtitle {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .account-link {
        color: #ffffff;
        text-decoration: underline;
        text-underline-offset: 3px;
        font-weight: 500;
        transition: opacity 0.2s;
    }
    .account-link:hover { opacity: 0.7; }

    /* ========== FORM INPUTS ========== */
    .form-group {
        margin-bottom: 16px;
    }

    .input-floating {
        position: relative;
    }

    .checkout-input {
        width: 100%;
        background: transparent;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 20px 16px 8px;
        color: #ffffff;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .checkout-input:focus {
        border-color: #ffffff;
        box-shadow: 0 0 0 1px rgba(255,255,255,0.1);
    }

    .checkout-textarea {
        resize: none;
        min-height: 100px;
    }

    .floating-label {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 0.875rem;
        pointer-events: none;
        transition: all 0.2s ease;
    }

    .checkout-textarea ~ .floating-label {
        top: 20px;
        transform: translateY(0);
    }

    .checkout-input:focus ~ .floating-label,
    .checkout-input:not(:placeholder-shown) ~ .floating-label,
    .has-value .floating-label {
        top: 10px;
        transform: translateY(0);
        font-size: 0.6875rem;
        color: #9ca3af;
    }

    .checkout-textarea:focus ~ .floating-label,
    .checkout-textarea:not(:placeholder-shown) ~ .floating-label {
        top: 8px;
        font-size: 0.6875rem;
        color: #9ca3af;
    }

    .input-hint {
        font-size: 0.6875rem;
        color: #4b5563;
        margin-top: 6px;
        padding-left: 4px;
    }

    .input-error {
        font-size: 0.75rem;
        color: #f87171;
        margin-top: 4px;
        padding-left: 4px;
    }

    .checkout-select {
        cursor: pointer;
        padding-right: 40px;
    }

    .checkout-select option {
        background: #111;
        color: #fff;
    }

    .select-arrow {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }

    .input-with-icon {
        padding-right: 48px;
    }

    .input-icon-right {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }

    .char-counter {
        text-align: right;
        font-size: 0.6875rem;
        color: #4b5563;
        margin-top: 4px;
    }

    /* ========== SHIPPING SECTION ========== */
    .shipping-notice {
        background: #111;
        border-radius: 10px;
        padding: 16px 20px;
        color: #6b7280;
        font-size: 0.8125rem;
        line-height: 1.5;
    }

    .shipping-options {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* ========== UNIFIED RADIO CARD (Shipping) ========== */
    .radio-card {
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 16px 56px 16px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: block;
    }

    .radio-card:hover {
        border-color: #3a3a3a;
        background: rgba(255,255,255,0.01);
    }

    .radio-card.selected {
        border-color: #ffffff;
        background: rgba(255,255,255,0.02);
    }

    .radio-card-content {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .radio-card-icon {
        color: #9ca3af;
        flex-shrink: 0;
    }

    .radio-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .radio-card-title {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .radio-card-desc {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .radio-card-price {
        font-size: 0.875rem;
        font-weight: 600;
        color: #9ca3af;
        white-space: nowrap;
    }

    /* ========== RADIO DOT (Centered, Clean) ========== */
    .radio-dot {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #3a3a3a;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .radio-dot::after {
        content: '';
        width: 0;
        height: 0;
        background: #ffffff;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .selected > .radio-dot,
    .radio-card.selected .radio-dot {
        border-color: #ffffff;
    }

    .selected > .radio-dot::after,
    .radio-card.selected .radio-dot::after {
        width: 10px;
        height: 10px;
    }

    /* ========== RADIO DOT SMALL (Sub-Options) ========== */
    .radio-dot-sm {
        width: 18px;
        height: 18px;
        min-width: 18px;
        border-radius: 50%;
        border: 2px solid #3a3a3a;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: auto;
    }

    .radio-dot-sm::after {
        content: '';
        width: 0;
        height: 0;
        background: #ffffff;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .sub-option.selected .radio-dot-sm {
        border-color: #ffffff;
    }

    .sub-option.selected .radio-dot-sm::after {
        width: 8px;
        height: 8px;
    }

    /* ========== PAYMENT SECTION ========== */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .payment-group {
        display: flex;
        flex-direction: column;
    }

    .payment-option {
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 16px 56px 16px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .payment-option:hover {
        border-color: #3a3a3a;
    }

    .payment-option.selected {
        border-color: #ffffff;
        background: rgba(255,255,255,0.02);
    }

    .payment-option-content {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .payment-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bank-icon {
        background: linear-gradient(135deg, #1a365d, #2563eb);
        color: #fff;
    }

    .ewallet-icon {
        background: linear-gradient(135deg, #065f46, #10b981);
        color: #fff;
    }

    .cod-icon {
        background: linear-gradient(135deg, #78350f, #f59e0b);
        color: #fff;
    }

    .payment-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .payment-name {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .payment-selected-channel {
        font-size: 0.6875rem;
        color: #9ca3af;
    }

    .payment-chevron {
        color: #6b7280;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .payment-option.expanded .payment-chevron {
        transform: rotate(180deg);
    }

    /* ========== PAYMENT SUB-OPTIONS ========== */
    .payment-sub-options {
        border: 1px solid #1a1a1a;
        border-top: none;
        border-radius: 0 0 12px 12px;
        background: #0d0d0d;
        padding: 4px 8px 8px;
        margin-top: -8px;
        animation: slideDown 0.25s ease;
        overflow: hidden;
    }

    .sub-option-header {
        padding: 12px 12px 8px;
    }

    .sub-option-badge {
        display: inline-block;
        padding: 6px 16px;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #d1d5db;
        background: transparent;
    }

    .sub-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .sub-option:hover {
        background: rgba(255,255,255,0.03);
    }

    .sub-option + .sub-option {
        border-top: 1px solid #1a1a1a;
    }

    .sub-option-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sub-option-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .sub-option-name {
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .sub-option-desc {
        font-size: 0.6875rem;
        color: #6b7280;
    }

    /* ========== RIGHT COLUMN ========== */
    .checkout-right {
        position: relative;
    }

    .order-summary-sticky {
        position: sticky;
        top: 80px;
        background: #0a0a0a;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        padding: 24px;
    }

    /* ========== PRODUCT CARD ========== */
    .order-product-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-bottom: 16px;
        margin-bottom: 16px;
        border-bottom: 1px solid #1a1a1a;
    }

    .product-thumb {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #1a1a1a;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
    }

    .product-details {
        flex: 1;
        min-width: 0;
    }

    .product-name {
        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 2px;
    }

    .product-variant {
        font-size: 0.6875rem;
        color: #6b7280;
    }

    .product-quantity {
        font-size: 0.6875rem;
        color: #6b7280;
        margin-top: 1px;
    }

    .product-price {
        font-size: 0.875rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ========== QUANTITY SELECTOR ========== */
    .quantity-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        margin-bottom: 12px;
        border-bottom: 1px solid #1a1a1a;
    }

    .quantity-label {
        font-size: 0.8125rem;
        color: #9ca3af;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.15s;
    }

    .qty-btn:hover {
        background: #1a1a1a;
        color: #fff;
    }

    .qty-input {
        width: 40px;
        text-align: center;
        background: transparent;
        border: none;
        border-left: 1px solid #2a2a2a;
        border-right: 1px solid #2a2a2a;
        color: #ffffff;
        font-size: 0.8125rem;
        font-family: 'Inter', sans-serif;
        height: 32px;
        -moz-appearance: textfield;
        outline: none;
    }

    .qty-input::-webkit-inner-spin-button,
    .qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }

    .stock-info {
        font-size: 0.6875rem;
        color: #4b5563;
    }

    /* ========== ADDONS (Shipping Message, Voucher) ========== */
    .checkout-addon {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border: 1px solid #1a1a1a;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 8px;
    }

    .checkout-addon:hover {
        border-color: #2a2a2a;
        background: rgba(255,255,255,0.01);
    }

    .addon-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .addon-text {
        font-size: 0.8125rem;
        color: #9ca3af;
    }

    .voucher-addon svg:first-child {
        color: #6b7280;
    }

    .shipping-message-area,
    .voucher-input-area {
        padding: 0 4px;
        margin-bottom: 8px;
        animation: slideDown 0.2s ease;
    }

    .shipping-message-input {
        width: 100%;
        background: #111;
        border: 1px solid #2a2a2a;
        border-radius: 10px;
        padding: 12px 14px;
        color: #fff;
        font-size: 0.8125rem;
        font-family: 'Inter', sans-serif;
        resize: none;
        outline: none;
        transition: border-color 0.2s;
    }

    .shipping-message-input:focus {
        border-color: #fff;
    }

    .voucher-input-area {
        display: flex;
        gap: 8px;
    }

    .voucher-input-area.hidden {
        display: none;
    }

    .voucher-input {
        flex: 1;
        background: #111;
        border: 1px solid #2a2a2a;
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 0.8125rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s;
    }

    .voucher-input:focus {
        border-color: #fff;
    }

    .voucher-apply-btn {
        background: #fff;
        color: #000;
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 0.8125rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }

    .voucher-apply-btn:hover {
        background: #e5e5e5;
    }

    /* ========== SUMMARY ========== */
    .summary-divider {
        height: 1px;
        background: #1a1a1a;
        margin: 16px 0;
    }

    .price-breakdown {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-label {
        font-size: 0.8125rem;
        color: #9ca3af;
    }

    .price-value {
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .price-value.discount {
        color: #6b7280;
    }

    .total-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-top: 1px solid #1a1a1a;
        margin-bottom: 16px;
    }

    .total-label {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .total-value {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    /* ========== SECURITY & NOTICE ========== */
    .security-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        padding: 10px 0;
        margin-bottom: 12px;
    }

    .security-badge svg {
        color: #6b7280;
        flex-shrink: 0;
    }

    .security-badge span {
        font-size: 0.6875rem;
        color: #6b7280;
    }

    .import-notice {
        background: rgba(234, 179, 8, 0.08);
        border: 1px solid rgba(234, 179, 8, 0.15);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
    }

    .import-notice p {
        font-size: 0.75rem;
        color: #ca8a04;
        line-height: 1.5;
        font-style: italic;
    }

    /* ========== ORDER BUTTON ========== */
    .order-button {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: #ffffff;
        color: #000000;
        border: none;
        border-radius: 14px;
        padding: 16px 24px;
        font-size: 0.9375rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.25s ease;
        letter-spacing: -0.01em;
    }

    .order-button:hover {
        background: #e5e5e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(255,255,255,0.15);
    }

    .order-button:active {
        transform: translateY(0);
    }

    /* ========== ANIMATIONS ========== */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hidden { display: none !important; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .order-summary-sticky {
            position: static;
        }

        .checkout-section {
            padding: 24px;
        }
    }

    @media (max-width: 640px) {
        .checkout-section {
            padding: 20px;
            border-radius: 12px;
        }

        .order-summary-sticky {
            padding: 20px;
            border-radius: 12px;
        }

        .order-product-card {
            flex-wrap: wrap;
        }

        .product-price {
            width: 100%;
            text-align: right;
            margin-top: 4px;
        }

        .sub-option {
            padding: 12px 8px;
            gap: 10px;
        }
    }
</style>

<script>
    // ========== PRICE & QUANTITY LOGIC ==========
    const pricePerUnit = {{ $product->price }};
    const maxStock = {{ $product->stock }};
    const qtyInput = document.getElementById('quantity');
    const displayQty = document.getElementById('display-qty');
    const summaryQtyText = document.getElementById('summary-qty-text');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function updateSummary() {
        const qty = Math.max(1, Math.min(parseInt(qtyInput.value) || 1, maxStock));
        qtyInput.value = qty;
        displayQty.textContent = qty;
        summaryQtyText.textContent = qty;
        const total = qty * pricePerUnit;
        summarySubtotal.textContent = formatRupiah(total);
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

    // ========== VOUCHER TOGGLE ==========
    document.getElementById('voucher-toggle').addEventListener('click', function() {
        const area = document.getElementById('voucher-area');
        area.classList.toggle('hidden');
    });

    // ========== SHIPPING METHOD SELECTION ==========
    document.querySelectorAll('.radio-card').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.radio-card').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
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

            // Deselect all payment options
            paymentOptions.forEach(o => {
                o.classList.remove('selected', 'expanded');
            });

            // Select this one
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;

            // Show/hide sub-options
            if (method === 'transfer') {
                bankSubOptions.style.display = 'block';
                ewalletSubOptions.style.display = 'none';
                this.classList.add('expanded');
                // Set channel from bank selection
                const checkedBank = document.querySelector('input[name="bank_channel"]:checked');
                if (checkedBank) {
                    paymentChannelInput.value = checkedBank.value;
                }
            } else if (method === 'ewallet') {
                bankSubOptions.style.display = 'none';
                ewalletSubOptions.style.display = 'block';
                this.classList.add('expanded');
                // Set channel from ewallet selection
                const checkedEwallet = document.querySelector('input[name="ewallet_channel"]:checked');
                if (checkedEwallet) {
                    paymentChannelInput.value = checkedEwallet.value;
                }
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
            const channel = this.dataset.channel;
            paymentChannelInput.value = channel;
            // Update label
            const name = this.querySelector('.sub-option-name').textContent;
            document.getElementById('transfer-selected-label').textContent = name;
        });
    });

    // ========== E-WALLET SUB-OPTION SELECTION ==========
    document.querySelectorAll('#ewallet-sub-options .sub-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('#ewallet-sub-options .sub-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            const channel = this.dataset.channel;
            paymentChannelInput.value = channel;
            // Update label
            const name = this.querySelector('.sub-option-name').textContent;
            document.getElementById('ewallet-selected-label').textContent = name;
        });
    });

    // ========== SHOW SHIPPING OPTIONS WHEN ADDRESS FILLED ==========
    const cityField = document.getElementById('checkout-city');
    const shippingOptions = document.getElementById('shipping-options');
    const shippingNotice = document.querySelector('.shipping-notice');

    function checkShippingVisibility() {
        if (addressField.value.trim().length > 5 || cityField.value.trim().length > 0) {
            shippingOptions.classList.remove('hidden');
            shippingNotice.style.display = 'none';
        } else {
            shippingOptions.classList.add('hidden');
            shippingNotice.style.display = 'block';
        }
    }

    addressField.addEventListener('input', checkShippingVisibility);
    cityField.addEventListener('input', checkShippingVisibility);
    checkShippingVisibility();

    // ========== INIT: Set default channel ==========
    (function() {
        const checkedBank = document.querySelector('input[name="bank_channel"]:checked');
        if (checkedBank) {
            paymentChannelInput.value = checkedBank.value;
            const parent = checkedBank.closest('.sub-option');
            if (parent) {
                const name = parent.querySelector('.sub-option-name').textContent;
                document.getElementById('transfer-selected-label').textContent = name;
            }
        }
        // Show bank sub-options by default since Transfer Bank is selected
        bankSubOptions.style.display = 'block';
        document.getElementById('payment-transfer').classList.add('expanded');
    })();
</script>

@endsection
