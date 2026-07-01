@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 mb-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Sinestesia.co Logo" class="w-10 h-10 object-cover rounded-sm bg-white">
                <span class="font-bold text-xl tracking-widest uppercase">Sinestesia.co</span>
            </div>
            <h1 class="text-2xl font-bold">Buat Akun Baru</h1>
            <p class="text-gray-400 mt-1 text-sm">Bergabung dengan Sinestesia.co</p>
        </div>

        {{-- Form --}}
        <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="John Doe"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="john@example.com"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-5">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">No. HP <span class="text-gray-600">(opsional)</span></label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="0812xxxxxxxx"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                    >
                </div>

                {{-- Address --}}
                <div class="mb-5">
                    <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Alamat <span class="text-gray-600">(opsional)</span></label>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="Jl. Contoh No. 1, Kota..."
                        rows="2"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none"
                    >{{ old('address') }}</textarea>
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min. 6 karakter"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                        required
                    >
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10">
                    Buat Akun
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-white font-medium hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
