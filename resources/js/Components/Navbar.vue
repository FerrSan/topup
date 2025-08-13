<template>
    <nav class="sticky top-0 z-50 bg-dark-surface/95 backdrop-blur-md border-b border-white/10">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center space-x-2">
                    <ApplicationLogo class="w-10 h-10" />
                    <span class="text-xl font-bold text-white">TopUpGame</span>
                </Link>
                
                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form @submit.prevent="search" class="w-full">
                        <div class="relative">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search games..." 
                                class="w-full px-4 py-2 pl-10 bg-zinc-900/60 border border-white/10 rounded-xl text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all"
                            >
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
                    
                    <template v-if="$page.props.auth.user">
                        <!-- User Dropdown -->
                        <Dropdown>
                            <template #trigger>
                                <button class="flex items-center space-x-2 px-4 py-2 bg-zinc-900/60 rounded-xl hover:bg-zinc-900 transition">
                                    <img :src="$page.props.auth.user.avatar_url" alt="Avatar" class="w-8 h-8 rounded-lg">
                                    <span class="text-white">{{ $page.props.auth.user.name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.index')">Profile</DropdownLink>
                                <DropdownLink :href="route('profile.orders')">My Orders</DropdownLink>
                                <hr class="my-2 border-white/10">
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Logout
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="px-4 py-2 text-gray-300 hover:text-white transition">Login</Link>
                        <Link :href="route('register')" class="px-4 py-2 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">Register</Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const searchQuery = ref('');

const search = () => {
    if (searchQuery.value) {
        router.get(route('games.search'), { q: searchQuery.value });
    }
};
</script>