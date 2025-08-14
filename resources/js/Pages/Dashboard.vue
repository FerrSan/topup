<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <AuthenticatedLayout title="Dashboard">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ $page.props.auth.user.name }}!</h1>
                <p class="text-gray-400">Here's what's happening with your account today.</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Orders</p>
                            <p class="text-2xl font-bold text-white">{{ stats.totalOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Successful Orders</p>
                            <p class="text-2xl font-bold text-white">{{ stats.successOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-accent-primary/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Spent</p>
                            <p class="text-2xl font-bold text-white">{{ formatCurrency(stats.totalSpent) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Recent Orders</h2>
                    <Link :href="route('profile.orders')" class="text-accent-primary hover:text-accent-secondary">
                        View All →
                    </Link>
                </div>

                <div v-if="recentOrders.length > 0" class="space-y-4">
                    <div v-for="order in recentOrders" :key="order.id" 
                         class="flex items-center justify-between p-4 bg-zinc-800/50 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <img :src="order.game.icon_url" :alt="order.game.name" 
                                 class="w-12 h-12 rounded-lg">
                            <div>
                                <h3 class="text-white font-medium">{{ order.game.name }}</h3>
                                <p class="text-gray-400 text-sm">{{ order.product.name }}</p>
                                <p class="text-gray-500 text-xs">{{ formatDate(order.created_at) }}</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-white font-semibold">{{ formatCurrency(order.grand_total) }}</p>
                            <span class="text-xs px-2 py-1 rounded" 
                                  :class="getStatusColor(order.status)">
                                {{ order.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-8">
                    <p class="text-gray-400">No orders yet</p>
                    <Link :href="route('games.index')" 
                          class="inline-block mt-4 px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                        Start Shopping
                    </Link>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link :href="route('games.index')" 
                      class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 hover:bg-zinc-900 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Browse Games</h3>
                        <p class="text-gray-400 text-sm mt-1">Find your favorite games</p>
                    </div>
                </Link>

                <Link :href="route('invoice.check')" 
                      class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 hover:bg-zinc-900 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Check Invoice</h3>
                        <p class="text-gray-400 text-sm mt-1">Track your orders</p>
                    </div>
                </Link>

                <Link :href="route('profile.edit')" 
                      class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 hover:bg-zinc-900 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Edit Profile</h3>
                        <p class="text-gray-400 text-sm mt-1">Update your info</p>
                    </div>
                </Link>

                <Link :href="route('profile.testimonials')" 
                      class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 hover:bg-zinc-900 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.396-1.21 1.502-1.21 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">My Reviews</h3>
                        <p class="text-gray-400 text-sm mt-1">View your reviews</p>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    stats: Object,
    recentOrders: Array
})

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

const getStatusColor = (status) => {
    const colors = {
        'SUCCESS': 'bg-green-500/20 text-green-500',
        'PENDING': 'bg-yellow-500/20 text-yellow-500',
        'FAILED': 'bg-red-500/20 text-red-500',
        'PROCESSING': 'bg-blue-500/20 text-blue-500'
    }
    return colors[status] || 'bg-gray-500/20 text-gray-400'
}
</script>