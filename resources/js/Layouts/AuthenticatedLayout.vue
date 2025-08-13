<template>
    <AppLayout :title="title">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 sticky top-24">
                        <div class="text-center mb-6">
                            <img :src="$page.props.auth.user.avatar_url" alt="Avatar" 
                                class="w-20 h-20 rounded-full mx-auto mb-3">
                            <h3 class="text-white font-semibold">{{ $page.props.auth.user.name }}</h3>
                            <p class="text-gray-400 text-sm">@{{ $page.props.auth.user.username }}</p>
                        </div>
                        
                        <nav class="space-y-2">
                            <NavLink :href="route('profile.index')" :active="route().current('profile.index')">
                                Profile Overview
                            </NavLink>
                            <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')">
                                Edit Profile
                            </NavLink>
                            <NavLink :href="route('profile.orders')" :active="route().current('profile.orders')">
                                Order History
                            </NavLink>
                            <NavLink :href="route('profile.testimonials')" :active="route().current('profile.testimonials')">
                                My Reviews
                            </NavLink>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <slot />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';

defineProps({
    title: String,
});
</script>