<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'TopUp Game'))</title>

    <!-- SEO Meta -->
    <meta name="description" content="@yield('description', 'Top up game online termurah dan terpercaya. Proses cepat, aman, dan otomatis 24/7.')">
    <meta name="keywords" content="top up game, diamond ml, uc pubg, primogem genshin">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:400,500,600,700" rel="stylesheet" />

    <!-- Vite -->
    {{-- PILIH SALAH SATU CARA:
         A) Kalau file resources/css/app.css ADA, pakai baris di bawah:
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- B) Kalau CSS di-import dari JS, ganti dengan:
         @vite('resources/js/app.js')
         dan pastikan di resources/js/app.js ada: import '../css/app.css'
    --}}

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-dark-bg text-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-dark-surface/95 backdrop-blur-md border-b border-white/10">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-zinc-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">TopUpGame</span>
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search games..."
                                   class="w-full px-4 py-2 pl-10 bg-zinc-900/60 border border-white/10 rounded-xl text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </form>
                </div>

                <!-- Right Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Check Invoice -->
                    <a href="{{ route('invoice.check') }}" class="hidden sm:flex items-center space-x-2 px-4 py-2 text-gray-300 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Check Invoice</span>
                    </a>

                    @auth
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-zinc-900/60 rounded-xl hover:bg-zinc-900 transition">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-lg">
                                <span class="text-white">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-zinc-900 rounded-xl shadow-lg ring-1 ring-white/10 py-2">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">Profile</a>
                                <a href="{{ route('profile.orders') }}" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">My Orders</a>
                                <hr class="my-2 border-white/10">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-white transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-surface border-t border-white/10 mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-white font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400 text-sm">Platform top-up game terpercaya dengan proses otomatis 24/7. Aman, cepat, dan terjamin.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('games.index') }}" class="text-gray-400 hover:text-white text-sm">All Games</a></li>
                        <li><a href="{{ route('invoice.check') }}" class="text-gray-400 hover:text-white text-sm">Check Invoice</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Contact</a></li>
                    </ul>
                </div>

                <!-- Payment Methods -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Payment Methods</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">QRIS</span>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">GoPay</span>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">OVO</span>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">DANA</span>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">BCA</span>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">BNI</span>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2 text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>support@topupgame.com</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-white/10 text-center text-gray-400 text-sm">
                <p>&copy; 2024 TopUpGame. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50"></div>

    @livewireScripts
    @stack('scripts')

    <!-- Realtime Updates -->
    <script>
        @if(isset($order))
        Echo.private('orders.{{ $order->invoice_no }}')
            .listen('.order.paid', (e) => {
                showToast('Payment received! Processing your order...', 'success');
                setTimeout(() => location.reload(), 2000);
            })
            .listen('.order.processing', (e) => {
                showToast('Processing your top-up...', 'info');
            })
            .listen('.order.completed', (e) => {
                showToast('Top-up completed successfully!', 'success');
                setTimeout(() => location.reload(), 2000);
            })
            .listen('.order.failed', (e) => {
                showToast('Top-up failed. Refund will be processed.', 'error');
                setTimeout(() => location.reload(), 2000);
            });
        @endif
    </script>
</body>
</html>
