@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="min-h-screen flex items-center justify-center py-10 px-4 bg-black">
    <div class="w-full max-w-md bg-white text-gray-800 rounded-xl overflow-hidden shadow-2xl relative">
        
        {{-- Header --}}
        <div class="border-b border-gray-200 px-4 py-4 flex items-center bg-white sticky top-0 z-10">
            <a href="{{ route('user.orders') }}" class="text-orange-600 hover:text-orange-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Pembayaran</h1>
        </div>

        {{-- Content --}}
        <div class="p-4 space-y-6">
            {{-- Total Pembayaran --}}
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <span class="text-gray-600 text-sm font-medium">Total Pembayaran</span>
                <span class="text-orange-600 font-bold text-lg">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Deadline --}}
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <span class="text-gray-600 text-sm font-medium">Pembayaran Dalam</span>
                <div class="text-right">
                    <span class="text-orange-600 font-bold block">
                        {{-- JS countdown placeholder --}}
                        <span id="countdown">24:00:00</span>
                    </span>
                    <span class="text-xs text-gray-500">Jatuh tempo {{ \Carbon\Carbon::parse($order->payment_deadline)->format('d M Y, H:i') }}</span>
                </div>
            </div>

            {{-- Payment Channel --}}
            <div class="flex items-center space-x-3 py-2">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center p-2 border border-gray-200">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="max-w-full max-h-full object-contain rounded">
                </div>
                <div>
                    <p class="font-bold text-gray-800">
                        @if($order->payment_method === 'transfer')
                            Transfer Bank ({{ strtoupper($order->payment_channel ?? 'Manual') }})
                        @elseif($order->payment_method === 'ewallet')
                            E-Wallet ({{ strtoupper($order->payment_channel ?? 'Manual') }})
                        @else
                            {{ strtoupper($order->payment_method) }}
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">Selesaikan pembayaran sesuai metode</p>
                </div>
            </div>

            {{-- Payment Code --}}
            <div class="mt-4 border-2 border-orange-500 rounded-xl p-4 bg-orange-50/30">
                <p class="text-gray-600 text-sm mb-1">Kode Pembayaran</p>
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-black tracking-wider text-orange-600" id="payment-code">{{ $order->payment_code }}</p>
                    <button onclick="copyCode()" class="text-orange-500 hover:text-orange-700 text-sm font-medium uppercase tracking-wider">
                        Salin
                    </button>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mt-6 border-t border-gray-200 pt-4">
                <button class="w-full flex justify-between items-center text-left py-2 font-medium text-gray-700 hover:text-orange-600" id="toggle-instruction">
                    <span>Petunjuk Pembayaran</span>
                    <svg class="w-5 h-5 transform transition-transform rotate-180" id="instruction-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-2 space-y-4 text-sm text-gray-600" id="instruction-content">
                    <div class="flex space-x-3">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</div>
                        <p>Lakukan pembayaran melalui ATM, M-Banking, atau agen terdekat sesuai dengan channel yang dipilih.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</div>
                        <p>Masukkan <span class="font-bold text-gray-800">Kode Pembayaran</span> di atas pada saat diminta nomor tagihan/rekening tujuan.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</div>
                        <p>Pastikan nominal transfer tepat sebesar <span class="font-bold text-orange-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</div>
                        <p>Setelah pembayaran berhasil, pesanan Anda akan otomatis diverifikasi oleh sistem / admin.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Button --}}
        <div class="p-4 bg-white border-t border-gray-100">
            <a href="{{ route('user.orders') }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold py-3 rounded-lg transition-colors">
                OK
            </a>
        </div>

    </div>
</div>

<script>
    // Toggle Instruction
    const toggleBtn = document.getElementById('toggle-instruction');
    const content = document.getElementById('instruction-content');
    const icon = document.getElementById('instruction-icon');

    toggleBtn.addEventListener('click', () => {
        content.classList.toggle('hidden');
        if(content.classList.contains('hidden')) {
            icon.classList.remove('rotate-180');
        } else {
            icon.classList.add('rotate-180');
        }
    });

    // Copy Code
    function copyCode() {
        const code = document.getElementById('payment-code').innerText;
        navigator.clipboard.writeText(code).then(() => {
            alert('Kode pembayaran disalin!');
        });
    }

    // Countdown Timer
    const deadline = new Date("{{ $order->payment_deadline->toISOString() }}").getTime();
    const countdownEl = document.getElementById('countdown');

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = deadline - now;

        if (distance < 0) {
            countdownEl.innerHTML = '<span style="color: #ef4444;">Waktu Habis</span>';
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.innerHTML = `${hours.toString().padStart(2, '0')} jam ${minutes.toString().padStart(2, '0')} menit ${seconds.toString().padStart(2, '0')} detik`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endsection
