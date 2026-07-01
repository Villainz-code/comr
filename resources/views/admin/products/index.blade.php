@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola semua produk toko')

@section('content')

{{-- Header Actions --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-gray-400 text-sm">{{ $products->total() }} produk ditemukan</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center bg-white text-black font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Category Filter --}}
<div class="flex flex-wrap items-center gap-2 mb-6">
    <span class="text-gray-500 text-xs font-semibold uppercase tracking-wider mr-1">Filter:</span>
    <a href="{{ route('admin.products.index') }}"
       class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all {{ !request('category') ? 'bg-white text-black' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200' }}">
        Semua
    </a>
    @foreach($categories as $category)
        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all {{ request('category') == $category->id ? 'bg-white text-black' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200' }}">
            {{ $category->name }}
        </a>
    @endforeach
</div>

{{-- Products Table --}}
<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-left">
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-800/20 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-gray-500 text-xs">{{ Str::limit($product->slug, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-800 text-gray-300 text-xs px-2.5 py-1 rounded-full">{{ $product->category->name }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $product->stock <= 5 ? 'text-red-400' : 'text-gray-300' }} font-medium">{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->status === 'active')
                            <span class="bg-green-900/50 text-green-300 text-xs px-2.5 py-1 rounded-full font-medium">Aktif</span>
                        @else
                            <span class="bg-gray-800 text-gray-400 text-xs px-2.5 py-1 rounded-full font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-3 py-1.5 rounded text-xs transition-all">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-400 hover:text-red-300 border border-red-900/50 hover:border-red-700 px-3 py-1.5 rounded text-xs transition-all">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p>Belum ada produk</p>
                        <a href="{{ route('admin.products.create') }}" class="text-white text-sm mt-2 inline-block hover:underline">Tambah produk pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">
        {{ $products->links() }}
    </div>
    @endif
</div>

@endsection
