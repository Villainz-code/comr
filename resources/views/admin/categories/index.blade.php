@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')
@section('page-subtitle', 'Kelola kategori produk')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center bg-white text-black font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-left">
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Jumlah Produk</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($categories as $i => $category)
                <tr class="hover:bg-gray-800/20 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ $categories->firstItem() + $i }}</td>
                    <td class="px-6 py-4 font-semibold">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-400 max-w-xs">{{ Str::limit($category->description, 60) ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-800 text-gray-300 text-xs px-2.5 py-1 rounded-full font-medium">{{ $category->products_count }} produk</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="text-gray-400 hover:text-white border border-gray-700 hover:border-gray-400 px-3 py-1.5 rounded text-xs transition-all">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Hapus kategori ini?')">
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
                    <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                        <p>Belum ada kategori</p>
                        <a href="{{ route('admin.categories.create') }}" class="text-white text-sm mt-2 inline-block hover:underline">Tambah kategori →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">
        {{ $categories->links() }}
    </div>
    @endif
</div>

@endsection
