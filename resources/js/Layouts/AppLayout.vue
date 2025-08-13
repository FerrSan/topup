<template>
    <div class="min-h-screen bg-dark-bg text-gray-100">
        <Head :title="title" />
        
        <!-- Navbar -->
        <Navbar />
        
        <!-- Main Content -->
        <main>
            <slot />
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
                            <li>
                                <Link :href="route('games.index')" class="text-gray-400 hover:text-white text-sm">All Games</Link>
                            </li>
                            <li>
                                <Link :href="route('invoice.check')" class="text-gray-400 hover:text-white text-sm">Check Invoice</Link>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white text-sm">FAQ</a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white text-sm">Contact</a>
                            </li>
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
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    title: String,
});
</script>

