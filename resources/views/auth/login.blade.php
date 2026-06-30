@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 mb-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Sinestesia.co Logo" class="w-10 h-10 object-cover rounded-sm bg-white">
                <span class="font-bold text-xl tracking-widest uppercase">Sinestesia.co</span>
            </div>
            <h1 class="text-2xl font-bold">Selamat Datang Kembali</h1>
            <p class="text-gray-400 mt-1 text-sm">Masuk ke akun Anda</p>
        </div>

        {{-- Form --}}
        <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@sinestesia.co"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 transition-colors @error('email') border-red-500 @enderror"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 transition-colors @error('password') border-red-500 @enderror"
                        autocomplete="current-password"
                        required
                    >
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center mb-6">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-600 bg-black text-white focus:ring-0">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="login-btn"
                    class="w-full bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-white font-medium hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>


    </div>
</div>
@endsection
