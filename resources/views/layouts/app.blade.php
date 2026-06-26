<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COMR Mini - Toko Pakaian Online Premium. Temukan koleksi fashion terbaik dengan gaya modern.">
    <title>@yield('title', 'COMR Mini') | COMR Mini</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-link-hover {
            position: relative;
        }
        .nav-link-hover::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: white;
            transition: width 0.3s ease;
        }
        .nav-link-hover:hover::after {
            width: 100%;
        }
        .btn-primary {
            background: #ffffff;
            color: #000000;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #e0e0e0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .flash-success { animation: slideInDown 0.4s ease; }
        .flash-error { animation: slideInDown 0.4s ease; }
        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-black border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="w-8 h-8 bg-white rounded-sm flex items-center justify-center">
                        <span class="text-black font-black text-xs">CM</span>
                    </div>
                    <span class="font-bold text-lg tracking-widest uppercase">COMR Mini</span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    @guest
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">Home</a>
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-sm text-sm font-semibold">Register</a>
                    @endguest

                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('user.dashboard') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">Dashboard</a>
                            <a href="{{ route('user.shop') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">Shop</a>
                            <a href="{{ route('user.orders') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">My Orders</a>
                            <a href="{{ route('user.profile') }}" class="text-gray-300 hover:text-white text-sm font-medium nav-link-hover transition-colors">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-red-400 text-sm font-medium transition-colors">Logout</button>
                            </form>
                        @endif
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" class="md:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-800 pt-4">
                @guest
                    <a href="{{ route('home') }}" class="block py-2 text-gray-300 hover:text-white text-sm">Home</a>
                    <a href="{{ route('login') }}" class="block py-2 text-gray-300 hover:text-white text-sm">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 text-white font-semibold text-sm">Register</a>
                @endguest
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('user.dashboard') }}" class="block py-2 text-gray-300 hover:text-white text-sm">Dashboard</a>
                        <a href="{{ route('user.shop') }}" class="block py-2 text-gray-300 hover:text-white text-sm">Shop</a>
                        <a href="{{ route('user.orders') }}" class="block py-2 text-gray-300 hover:text-white text-sm">My Orders</a>
                        <a href="{{ route('user.profile') }}" class="block py-2 text-gray-300 hover:text-white text-sm">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block py-2 text-red-400 text-sm w-full text-left">Logout</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success bg-green-900 border border-green-700 text-green-200 px-4 py-3 text-sm text-center" id="flash-msg">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-error bg-red-900 border border-red-700 text-red-200 px-4 py-3 text-sm text-center" id="flash-msg">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-black border-t border-gray-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-white rounded-sm flex items-center justify-center">
                        <span class="text-black font-black text-xs">CM</span>
                    </div>
                    <span class="font-bold tracking-widest uppercase text-sm">COMR Mini</span>
                </div>
                <p class="text-gray-500 text-xs">© {{ date('Y') }} COMR Mini. Proyek UAS Pemrograman Web.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Auto-hide flash messages
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
