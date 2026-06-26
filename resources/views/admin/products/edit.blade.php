@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Perbarui informasi produk')

@section('content')

<div class="max-w-3xl">
    <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori <span class="text-red-400">*</span></label>
                    <select name="category_id"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status <span class="text-red-400">*</span></label>
                    <select name="status"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors">
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                        min="0" step="1000"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('price') border-red-500 @enderror">
                    @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                        min="0"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors @error('stock') border-red-500 @enderror">
                    @error('stock') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Image --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gambar Produk</label>
                    @if($product->image)
                        <div class="mb-4 flex items-center space-x-4">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-700">
                            <p class="text-gray-400 text-sm">Gambar saat ini. Upload baru untuk mengganti.</p>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-gray-500 transition-colors">
                        <input type="file" name="image" id="image-input" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                        <div id="image-preview" class="hidden mb-4">
                            <img id="preview-img" src="" alt="Preview" class="max-h-40 mx-auto rounded-lg">
                        </div>
                        <p class="text-gray-500 text-sm mb-3">Upload gambar baru (opsional)</p>
                        <button type="button" onclick="document.getElementById('image-input').click()"
                            class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-4 py-2 rounded-lg text-sm transition-all">
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
                    Perbarui Produk
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
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
