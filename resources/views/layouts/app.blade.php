<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sinestesia.co - Toko Pakaian Online Premium. Temukan koleksi fashion terbaik dengan gaya modern.">
    <title>@yield('title', 'Sinestesia.co') | Sinestesia.co</title>
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
                    <img src="{{ asset('images/logo.jpg') }}" alt="Sinestesia.co Logo" class="w-8 h-8 object-cover rounded-sm bg-white">
                    <span class="font-bold text-lg tracking-widest uppercase">Sinestesia.co</span>
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
                    <img src="{{ asset('images/logo.jpg') }}" alt="Sinestesia.co Logo" class="w-6 h-6 object-cover rounded-sm bg-white">
                    <span class="font-bold tracking-widest uppercase text-sm">Sinestesia.co</span>
                </div>
                <p class="text-gray-500 text-xs">© {{ date('Y') }} Sinestesia.co. Proyek UAS Pemrograman Web.</p>
            </div>
        </div>
    </footer>

    @auth
        @if(auth()->user()->role === 'customer')
            {{-- Floating Cart --}}
            <a href="{{ route('user.cart') }}" class="fixed bottom-6 right-6 z-50 bg-white text-black p-4 rounded-full shadow-lg shadow-white/10 hover:scale-110 hover:-translate-y-1 transition-all duration-300 group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-black">{{ $cartCount }}</span>
                @endif
            </a>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Custom notification function using SweetAlert2
        window.showNotification = function(message) {
            Swal.fire({
                title: `<div class="flex items-center space-x-3 text-left">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="block text-sm font-semibold text-white">Informasi</span>
                                <span class="block text-xs text-gray-400 font-normal mt-0.5">${message}</span>
                            </div>
                        </div>`,
                position: 'top',
                showConfirmButton: true,
                confirmButtonText: 'Mengerti',
                background: '#111111',
                color: '#ffffff',
                buttonsStyling: false,
                showClass: {
                    popup: 'animate-[slideInDown_0.3s_ease-out]'
                },
                hideClass: {
                    popup: 'animate-[slideOutUp_0.3s_ease-in]'
                },
                customClass: {
                    popup: 'border border-gray-800 rounded-xl mt-6 !flex-row items-center justify-between !w-auto min-w-[320px] sm:min-w-[450px] !py-3 !px-4 shadow-2xl bg-[#111111]',
                    title: '!m-0 !p-0 flex-1',
                    htmlContainer: '!hidden',
                    actions: '!m-0 !p-0',
                    confirmButton: 'px-4 py-2 rounded-lg m-0 bg-white text-black font-semibold text-xs hover:bg-gray-200 transition-colors'
                }
            });
        };

        // Replace native confirms with SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const confirmForms = document.querySelectorAll('form[onsubmit*="return confirm"]');
            confirmForms.forEach(form => {
                const onsubmitStr = form.getAttribute('onsubmit');
                const match = onsubmitStr.match(/confirm\(['"](.*?)['"]\)/);
                const message = match ? match[1] : 'Apakah Anda yakin?';
                
                form.removeAttribute('onsubmit');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: `<div class="flex items-center space-x-3 text-left">
                                    <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div>
                                        <span class="block text-sm font-semibold text-white">Konfirmasi</span>
                                        <span class="block text-xs text-gray-400 font-normal mt-0.5">${message}</span>
                                    </div>
                                </div>`,
                        position: 'top',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        background: '#111111',
                        color: '#ffffff',
                        buttonsStyling: false,
                        showClass: {
                            popup: 'animate-[slideInDown_0.3s_ease-out]'
                        },
                        hideClass: {
                            popup: 'animate-[slideOutUp_0.3s_ease-in]'
                        },
                        customClass: {
                            popup: 'border border-gray-800 rounded-xl mt-6 !flex-row items-center justify-between !w-auto min-w-[320px] sm:min-w-[450px] !py-3 !px-4 shadow-2xl bg-[#111111]',
                            title: '!m-0 !p-0 flex-1',
                            htmlContainer: '!hidden', // We put text in title
                            actions: '!m-0 !p-0 space-x-2 flex',
                            confirmButton: 'px-4 py-2 rounded-lg m-0 bg-white text-black font-semibold text-xs hover:bg-gray-200 transition-colors',
                            cancelButton: 'px-4 py-2 rounded-lg m-0 bg-gray-800 text-gray-300 font-semibold text-xs hover:bg-gray-700 transition-colors'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
