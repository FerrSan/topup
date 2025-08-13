<template>
    <div class="min-h-screen bg-dark-bg text-gray-100 flex flex-col justify-center">
        <Head :title="title" />
        
        <div class="container mx-auto px-4">
            <div class="flex justify-center mb-8">
                <Link :href="route('home')" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-zinc-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">TopUpGame</span>
                </Link>
            </div>
            
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>