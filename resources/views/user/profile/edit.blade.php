@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12">

    <div class="mb-8">
        <h1 class="text-2xl font-bold">Profil Saya</h1>
        <p class="text-gray-400 text-sm mt-1">Perbarui informasi akun Anda</p>
    </div>

    {{-- Profile Avatar with Edit Photo --}}
    <div class="flex items-center space-x-4 mb-8 bg-[#111] border border-gray-800 rounded-xl p-5">
        <div class="relative group">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                     alt="Foto Profil"
                     class="w-16 h-16 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center text-2xl font-bold flex-shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif

            {{-- Edit overlay on hover --}}
            <label for="photo-upload"
                   class="absolute inset-0 bg-black/60 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </label>
        </div>
        <div>
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
            <span class="inline-block bg-gray-800 text-gray-300 text-xs px-2.5 py-0.5 rounded-full mt-1">Customer</span>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Photo Upload Section --}}
            <div class="mb-6 pb-6 border-b border-gray-800">
                <label class="block text-sm font-medium text-gray-300 mb-3">Foto Profil</label>
                <div class="flex items-center gap-4">
                    {{-- Preview --}}
                    <div id="photo-preview-container" class="relative">
                        @if(auth()->user()->photo)
                            <img id="photo-preview" src="{{ asset('storage/' . auth()->user()->photo) }}"
                                 alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-700">
                        @else
                            <div id="photo-preview" class="w-20 h-20 bg-gray-700 rounded-full flex items-center justify-center text-3xl font-bold border-2 border-gray-700">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        {{-- Upload Button --}}
                        <input type="file" name="photo" id="photo-upload" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden"
                               onchange="previewPhoto(this)">
                        <label for="photo-upload"
                               class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-sm px-4 py-2.5 rounded-lg cursor-pointer transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Pilih Foto
                        </label>

                        {{-- Delete Photo --}}
                        @if(auth()->user()->photo)
                            <button type="button" onclick="document.getElementById('delete-photo-form').submit()"
                                    class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 text-sm ml-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        @endif

                        <p class="text-gray-500 text-xs mt-2">JPG, PNG, atau WebP. Maksimal 2MB.</p>
                        @error('photo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror

                        {{-- File name display --}}
                        <p id="photo-filename" class="text-green-400 text-xs mt-1 hidden"></p>
                    </div>
                </div>
            </div>

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

        {{-- Hidden Delete Photo Form --}}
        @if(auth()->user()->photo)
        <form id="delete-photo-form" method="POST" action="{{ route('user.profile.photo.delete') }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </div>

    {{-- Account Info --}}
    <div class="mt-4 bg-gray-900/20 border border-gray-800 rounded-xl p-4">
        <p class="text-gray-500 text-xs">Bergabung sejak: {{ auth()->user()->created_at->format('d M Y') }}</p>
    </div>

</div>

{{-- Photo Preview Script --}}
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const container = document.getElementById('photo-preview-container');
            container.innerHTML = `<img id="photo-preview" src="${e.target.result}" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-green-500/50">`;
        }

        reader.readAsDataURL(file);

        // Show filename
        const filenameEl = document.getElementById('photo-filename');
        filenameEl.textContent = '✓ ' + file.name;
        filenameEl.classList.remove('hidden');
    }
}
</script>
@endsection
