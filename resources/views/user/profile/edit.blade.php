@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12">

    <div class="mb-8">
        <h1 class="text-2xl font-bold">Profil Saya</h1>
        <p class="text-gray-400 text-sm mt-1">Perbarui informasi akun Anda</p>
    </div>

    {{-- Profile Avatar --}}
    <div class="flex items-center space-x-4 mb-8 bg-[#111] border border-gray-800 rounded-xl p-5">
        <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center text-2xl font-bold flex-shrink-0">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
            <span class="inline-block bg-gray-800 text-gray-300 text-xs px-2.5 py-0.5 rounded-full mt-1">Customer</span>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <form method="POST" action="{{ route('user.profile.update') }}">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email (readonly) --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-500 mb-2">Email <span class="text-gray-600 text-xs">(tidak dapat diubah)</span></label>
                <input type="email" value="{{ auth()->user()->email }}" disabled
                    class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3 text-gray-500 text-sm cursor-not-allowed">
            </div>

            {{-- Phone --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-300 mb-2">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                    placeholder="0812xxxxxxxx"
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors">
            </div>

            {{-- Address --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                <textarea name="address" rows="4"
                    placeholder="Alamat lengkap Anda..."
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none">{{ old('address', auth()->user()->address) }}</textarea>
                <p class="text-gray-600 text-xs mt-1">Alamat ini akan diisi otomatis saat memesan</p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-800">
                <button type="submit"
                    class="flex-1 bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
                <a href="{{ route('user.dashboard') }}"
                   class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-6 py-3 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="mt-4 bg-gray-900/20 border border-gray-800 rounded-xl p-4">
        <p class="text-gray-500 text-xs">Bergabung sejak: {{ auth()->user()->created_at->format('d MMMM Y') }}</p>
    </div>

</div>
@endsection
