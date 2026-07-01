@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Isi form di bawah untuk menambah produk baru')

@section('content')

<div class="max-w-3xl">
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        placeholder="Contoh: Sinestesia.co Classic Black Tee"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori <span class="text-red-400">*</span></label>
                    <select name="category_id"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('category_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status <span class="text-red-400">*</span></label>
                    <select name="status"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}"
                        placeholder="185000"
                        min="0" step="1000"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('price') border-red-500 @enderror">
                    @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock') }}"
                        placeholder="0"
                        min="0"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('stock') border-red-500 @enderror">
                    @error('stock') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sizes --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ukuran Tersedia <span class="text-xs text-gray-500 font-normal ml-2">(Kosongkan jika produk tidak memiliki varian ukuran)</span></label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['S', 'M', 'L', 'XL', 'XXL', 'ALL SIZE'] as $size)
                        <label class="inline-flex items-center bg-black border border-gray-700 rounded-lg px-4 py-2 cursor-pointer hover:border-gray-500 transition-colors">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ is_array(old('sizes')) && in_array($size, old('sizes')) ? 'checked' : '' }} class="mr-2 rounded border-gray-600 text-white focus:ring-white bg-gray-800">
                            <span class="text-sm text-gray-300">{{ $size }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('sizes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        placeholder="Deskripsi produk..."
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Image --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gambar Produk</label>
                    <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-gray-500 transition-colors" id="drop-zone">
                        <input type="file" name="image" id="image-input" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                        <div id="image-preview" class="hidden mb-4">
                            <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg">
                        </div>
                        <div id="upload-placeholder">
                            <svg class="w-10 h-10 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 text-sm mb-2">Klik untuk upload gambar</p>
                            <p class="text-gray-600 text-xs">JPG, PNG, WEBP - Max 2MB</p>
                        </div>
                        <button type="button" onclick="document.getElementById('image-input').click()"
                            class="mt-3 text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-4 py-2 rounded-lg text-sm transition-all">
                            Pilih Gambar
                        </button>
                    </div>
                    @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-800">
                <button type="submit"
                    class="bg-white text-black font-semibold px-6 py-3 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-6 py-3 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// ALL SIZE toggle logic
document.addEventListener('DOMContentLoaded', function() {
    const allCheckboxes = document.querySelectorAll('input[name="sizes[]"]');
    const individualSizes = ['S', 'M', 'L', 'XL', 'XXL'];
    let allSizeCheckbox = null;
    let sizeCheckboxes = [];

    allCheckboxes.forEach(cb => {
        if (cb.value === 'ALL SIZE') {
            allSizeCheckbox = cb;
        } else if (individualSizes.includes(cb.value)) {
            sizeCheckboxes.push(cb);
        }
    });

    if (allSizeCheckbox) {
        allSizeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                sizeCheckboxes.forEach(cb => cb.checked = true);
            } else {
                sizeCheckboxes.forEach(cb => cb.checked = false);
            }
        });

        sizeCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = sizeCheckboxes.every(c => c.checked);
                allSizeCheckbox.checked = allChecked;
            });
        });
    }
});
</script>

@endsection
