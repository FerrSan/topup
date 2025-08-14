<template>
    <div class="min-h-screen bg-dark-bg">
        <Navbar />
        
        <!-- Main Content dengan Sidebar -->
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 sticky top-24">
                        <nav class="space-y-2">
                            <NavLink :href="route('profile.index')" :active="route().current('profile.index')">
                                Profile
                            </NavLink>
                            <NavLink :href="route('profile.orders')" :active="route().current('profile.orders')">
                                My Orders
                            </NavLink>
                            <NavLink :href="route('profile.testimonials')" :active="route().current('profile.testimonials')">
                                My Reviews
                            </NavLink>
                        </nav>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="lg:col-span-3">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
import NavLink from '@/Components/NavLink.vue';
</script>