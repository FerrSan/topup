<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const user = page.props?.auth?.user ?? null
const menuOpen = ref(false)

function closeMenu(e) {
  // tutup kalau klik di luar dropdown
  if (!e.target.closest?.('[data-user-menu]')) menuOpen.value = false
}
if (typeof window !== 'undefined') window.addEventListener('click', closeMenu)
</script>

<template>
  <div class="bg-dark-bg text-gray-100 font-sans antialiased min-h-screen">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-dark-surface/95 backdrop-blur-md border-b border-white/10">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <Link :href="route('home')" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-zinc-900" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
              </svg>
            </div>
            <span class="text-xl font-bold text-white">TopUpGame</span>
          </Link>

          <!-- Search (Desktop) -->
          <div class="hidden md:flex flex-1 max-w-md mx-8">
            <form :action="route('search')" method="GET" class="w-full">
              <div class="relative">
                <input
                  type="text"
                  name="q"
                  placeholder="Search games..."
                  class="w-full px-4 py-2 pl-10 bg-zinc-900/60 border border-white/10 rounded-xl text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all"
                />
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </form>
          </div>

          <!-- Right Menu -->
          <div class="flex items-center space-x-4">
            <!-- Check Invoice -->
            <Link :href="route('invoice.check')" class="hidden sm:flex items-center space-x-2 px-4 py-2 text-gray-300 hover:text-white transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>Check Invoice</span>
            </Link>

            <!-- User -->
            <template v-if="user">
              <div class="relative" data-user-menu>
                <button @click.stop="menuOpen = !menuOpen" class="flex items-center space-x-2 px-4 py-2 bg-zinc-900/60 rounded-xl hover:bg-zinc-900 transition">
                  <img :src="user.avatar_url" alt="Avatar" class="w-8 h-8 rounded-lg" />
                  <span class="text-white">{{ user.name }}</span>
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <div
                  v-show="menuOpen"
                  class="absolute right-0 mt-2 w-48 bg-zinc-900 rounded-xl shadow-lg ring-1 ring-white/10 py-2"
                >
                  <Link :href="route('profile.index')" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">Profile</Link>
                  <Link :href="route('profile.orders')" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">My Orders</Link>
                  <hr class="my-2 border-white/10" />
                  <form :action="route('logout')" method="POST">
                    <input type="hidden" name="_token" :value="page.props.csrf_token || document.querySelector('meta[name=csrf-token]')?.content">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800">Logout</button>
                  </form>
                </div>
              </div>
            </template>
            <template v-else>
              <Link :href="route('login')" class="px-4 py-2 text-gray-300 hover:text-white transition">Login</Link>
              <Link :href="route('register')" class="px-4 py-2 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">Register</Link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page content -->
    <main class="min-h-screen">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-dark-surface border-t border-white/10 mt-20">
      <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-white font-semibold mb-4">About Us</h3>
            <p class="text-gray-400 text-sm">Platform top-up game terpercaya dengan proses otomatis 24/7. Aman, cepat, dan terjamin.</p>
          </div>

          <div>
            <h3 class="text-white font-semibold mb-4">Quick Links</h3>
            <ul class="space-y-2">
              <li><Link :href="route('games.index')" class="text-gray-400 hover:text-white text-sm">All Games</Link></li>
              <li><Link :href="route('invoice.check')" class="text-gray-400 hover:text-white text-sm">Check Invoice</Link></li>
              <li><a href="#" class="text-gray-400 hover:text-white text-sm">FAQ</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white text-sm">Contact</a></li>
            </ul>
          </div>

          <div>
            <h3 class="text-white font-semibold mb-4">Payment Methods</h3>
            <div class="grid grid-cols-3 gap-2">
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">QRIS</span></div>
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">GoPay</span></div>
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">OVO</span></div>
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">DANA</span></div>
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">BCA</span></div>
              <div class="bg-white/5 rounded-lg p-2 flex items-center justify-center"><span class="text-xs text-gray-400">BNI</span></div>
            </div>
          </div>

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
                  <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/>
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
  </div>
</template>
