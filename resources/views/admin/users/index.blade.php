@extends('layouts.admin')

@section('title', 'Data Customer')
@section('page-title', 'Data Customer')
@section('page-subtitle', 'Daftar semua customer terdaftar')

@section('content')

<div class="bg-[#111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <p class="text-gray-400 text-sm">{{ $users->total() }} customer terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-left">
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">No HP</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Order</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-4 text-gray-500 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($users as $i => $user)
                <tr class="hover:bg-gray-800/20 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ $users->firstItem() + $i }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $user->phone ?: '-' }}</td>
                    <td class="px-6 py-4 text-gray-400 max-w-[200px] truncate">{{ $user->address ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-800 text-gray-300 text-xs px-2.5 py-1 rounded-full">{{ $user->orders_count }} pesanan</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 border border-red-900/50 hover:border-red-700 px-3 py-1.5 rounded text-xs transition-all">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">Belum ada customer terdaftar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
