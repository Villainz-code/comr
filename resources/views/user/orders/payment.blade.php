@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="min-h-screen flex items-center justify-center py-10 px-4 bg-black">
    <div class="w-full max-w-md bg-[#0a0a0a] border border-[#2a2a2a] text-white rounded-xl overflow-hidden shadow-2xl relative">
        
        {{-- Header --}}
        <div class="border-b border-[#2a2a2a] px-4 py-4 flex items-center bg-[#0a0a0a] sticky top-0 z-10">
            <a href="{{ route('user.orders') }}" class="text-gray-400 hover:text-white mr-4 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Pembayaran</h1>
        </div>

        {{-- Content --}}
        <div class="p-4 space-y-6">
            {{-- Total Pembayaran --}}
            <div class="flex justify-between items-center border-b border-[#2a2a2a] pb-4">
                <span class="text-gray-400 text-sm font-medium">Total Pembayaran</span>
                <span class="text-white font-bold text-lg">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            {{-- Deadline --}}
            <div class="flex justify-between items-center border-b border-[#2a2a2a] pb-4">
                <span class="text-gray-400 text-sm font-medium">Pembayaran Dalam</span>
                <div class="text-right">
                    <span class="text-white font-bold block">
                        {{-- JS countdown placeholder --}}
                        <span id="countdown">24:00:00</span>
                    </span>
                    <span class="text-xs text-gray-500">Jatuh tempo {{ \Carbon\Carbon::parse($order->payment_deadline)->format('d M Y, H:i') }}</span>
                </div>
            </div>

            {{-- Payment Channel --}}
            <div class="flex items-center space-x-3 py-2">
                <div class="w-12 h-12 bg-[#1a1a1a] rounded-lg flex items-center justify-center p-2 border border-[#2a2a2a]">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="max-w-full max-h-full object-contain rounded">
                </div>
                <div>
                    <p class="font-bold text-white">
                        @if($order->payment_method === 'transfer')
                            Transfer Bank ({{ strtoupper($order->payment_channel ?? 'Manual') }})
                        @elseif($order->payment_method === 'ewallet')
                            E-Wallet ({{ strtoupper($order->payment_channel ?? 'Manual') }})
                        @else
                            {{ strtoupper($order->payment_method) }}
                        @endif
                    </p>
                    <p class="text-xs text-gray-400">Selesaikan pembayaran sesuai metode</p>
                </div>
            </div>

            {{-- Payment Code --}}
            <div class="mt-4 border border-[#2a2a2a] rounded-xl p-4 bg-[#111111]">
                <p class="text-gray-400 text-sm mb-1">Kode Pembayaran</p>
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-black tracking-wider text-white" id="payment-code">{{ $order->payment_code }}</p>
                    <button onclick="copyCode()" class="text-gray-400 hover:text-white text-sm font-medium uppercase tracking-wider transition-colors">
                        Salin
                    </button>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mt-6 border-t border-[#2a2a2a] pt-4">
                <button class="w-full flex justify-between items-center text-left py-2 font-medium text-gray-300 hover:text-white transition-colors" id="toggle-instruction">
                    <span>Petunjuk Pembayaran</span>
                    <svg class="w-5 h-5 transform transition-transform rotate-180" id="instruction-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="mt-4 space-y-4 text-sm text-gray-400" id="instruction-content">
                    <div class="flex space-x-3">
                        <div class="w-6 h-6 rounded-full bg-[#2a2a2a] text-white flex items-center justify-center text-xs font-bold shrink-0">1</div>
                        <p class="pt-0.5">Lakukan pembayaran melalui ATM, M-Banking, atau agen terdekat sesuai dengan channel yang dipilih.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-6 h-6 rounded-full bg-[#2a2a2a] text-white flex items-center justify-center text-xs font-bold shrink-0">2</div>
                        <p class="pt-0.5">Masukkan <span class="font-bold text-white">Kode Pembayaran</span> di atas pada saat diminta nomor tagihan/rekening tujuan.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-6 h-6 rounded-full bg-[#2a2a2a] text-white flex items-center justify-center text-xs font-bold shrink-0">3</div>
                        <p class="pt-0.5">Pastikan nominal transfer tepat sebesar <span class="font-bold text-white">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>.</p>
                    </div>
                    <div class="flex space-x-3">
                        <div class="w-6 h-6 rounded-full bg-[#2a2a2a] text-white flex items-center justify-center text-xs font-bold shrink-0">4</div>
                        <p class="pt-0.5">Setelah pembayaran berhasil, pesanan Anda akan otomatis diverifikasi oleh sistem / admin.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Button --}}
        <div class="p-4 bg-[#0a0a0a] border-t border-[#2a2a2a]">
            <a href="{{ route('user.orders') }}" class="block w-full bg-white hover:bg-gray-200 text-black text-center font-bold py-3 rounded-lg transition-colors">
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
            showNotification('Kode pembayaran disalin!');
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
