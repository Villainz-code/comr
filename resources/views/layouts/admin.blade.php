<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COMR Mini Admin Panel - Kelola toko pakaian online Anda.">
    <title>Admin | @yield('title', 'Dashboard') | COMR Mini</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            transition: all 0.2s ease;
            position: relative;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #ffffff;
            border-radius: 0 2px 2px 0;
        }
        .stat-card {
            background: #111111;
            border: 1px solid #222222;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            border-color: #444444;
            transform: translateY(-2px);
        }
        .flash-success { animation: slideInDown 0.4s ease; }
        .flash-error { animation: slideInDown 0.4s ease; }
        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-black border-r border-gray-800 flex flex-col flex-shrink-0 overflow-y-auto" id="sidebar">
        {{-- Logo --}}
        <div class="flex items-center space-x-3 p-6 border-b border-gray-800">
            <div class="w-8 h-8 bg-white rounded-sm flex items-center justify-center flex-shrink-0">
                <span class="text-black font-black text-xs">CM</span>
            </div>
            <div>
                <p class="font-bold text-sm tracking-widest uppercase">COMR Mini</p>
                <p class="text-gray-500 text-xs">Admin Panel</p>
            </div>
        </div>

        {{-- Admin Info --}}
        <div class="p-4 border-b border-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block bg-gray-700 text-gray-300 text-xs px-2 py-0.5 rounded-full">Admin</span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1">
            <p class="text-gray-600 text-xs uppercase tracking-widest font-semibold mb-3 px-3">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 text-sm {{ request()->routeIs('admin.dashboard') ? 'active text-white' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 text-sm {{ request()->routeIs('admin.products*') ? 'active text-white' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 text-sm {{ request()->routeIs('admin.categories*') ? 'active text-white' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.users') }}"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 text-sm {{ request()->routeIs('admin.users') ? 'active text-white' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Customers</span>
            </a>

            <a href="{{ route('admin.orders') }}"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 text-sm {{ request()->routeIs('admin.orders*') ? 'active text-white' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Orders</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-900/10 text-sm transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-black border-b border-gray-800 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
                <p class="text-gray-500 text-xs">@yield('page-subtitle', 'Kelola toko Anda')</p>
            </div>
            <div class="text-gray-500 text-xs">
                {{ now()->format('d M Y, H:i') }} WIB
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flash-success bg-green-900/50 border-b border-green-800 text-green-300 px-6 py-3 text-sm" id="flash-msg">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error bg-red-900/50 border-b border-red-800 text-red-300 px-6 py-3 text-sm" id="flash-msg">
                ✗ {{ session('error') }}
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-msg');
            if (msg) {
                msg.style.transition = 'opacity 0.5s ease';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            }
        }, 4000);
    </script>
</body>
</html>
