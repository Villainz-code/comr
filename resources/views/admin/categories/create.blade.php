@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')
@section('page-subtitle', 'Buat kategori produk baru')

@section('content')

<div class="max-w-lg">
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Contoh: T-Shirt"
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    placeholder="Deskripsi kategori (opsional)..."
                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-800">
                <button type="submit"
                    class="bg-white text-black font-semibold px-6 py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-6 py-3 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
