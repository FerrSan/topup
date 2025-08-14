<template>
    <AppLayout :title="game.name">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <ol class="flex items-center space-x-2 text-gray-400">
                    <li><Link :href="route('home')" class="hover:text-white">Home</Link></li>
                    <li>/</li>
                    <li><Link :href="route('games.index')" class="hover:text-white">Games</Link></li>
                    <li>/</li>
                    <li class="text-white">{{ game.name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Game Info -->
                    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-6">
                        <div class="flex items-start space-x-6">
                            <img :src="game.icon_url" :alt="game.name" class="w-24 h-24 rounded-xl">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h1 class="text-3xl font-bold text-white">{{ game.name }}</h1>
                                    <span v-if="game.is_featured" class="px-2 py-1 bg-accent-primary text-zinc-900 text-xs font-semibold rounded">
                                        FEATURED
                                    </span>
                                </div>
                                <p class="text-gray-400 mb-4">{{ game.publisher }}</p>
                                <p v-if="game.description" class="text-gray-300">{{ game.description }}</p>
                                
                                <div class="flex items-center space-x-4 mt-4 text-sm text-gray-500">
                                    <span>Category: {{ game.category?.toUpperCase() }}</span>
                                    <span>{{ formatNumber(game.view_count || 0) }} views</span>
                                    <span>{{ formatNumber(game.orders_count || 0) }} orders</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-6">
                        <h2 class="text-xl font-bold text-white mb-4">Select Top Up Amount</h2>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <button
                                v-for="product in game.products"
                                :key="product.id"
                                @click="selectedProduct = product"
                                :class="[
                                    'p-4 rounded-xl border-2 transition-all text-left',
                                    selectedProduct?.id === product.id
                                        ? 'border-accent-primary bg-accent-primary/10'
                                        : 'border-white/10 bg-zinc-800/50 hover:border-accent-primary/50'
                                ]"
                            >
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-white font-semibold">{{ product.name }}</span>
                                    <span v-if="product.is_hot" class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                        HOT
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-accent-primary font-bold">{{ formatCurrency(product.price) }}</span>
                                    <span v-if="product.original_price && product.original_price > product.price" 
                                        class="text-gray-500 line-through text-sm">
                                        {{ formatCurrency(product.original_price) }}
                                    </span>
                                </div>
                                <p v-if="product.process_time" class="text-gray-400 text-xs mt-1">
                                    Process: {{ product.process_time }}
                                </p>
                            </button>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <div v-if="selectedProduct" class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                        <h3 class="text-lg font-bold text-white mb-4">Player Information</h3>
                        
                        <form @submit.prevent="submitOrder" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-300 text-sm mb-2">User ID *</label>
                                    <input 
                                        v-model="form.player_uid"
                                        type="text" 
                                        required
                                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                        placeholder="Enter your User ID"
                                    />
                                </div>
                                
                                <div v-if="requiresServer">
                                    <label class="block text-gray-300 text-sm mb-2">Server ID *</label>
                                    <input 
                                        v-model="form.player_server"
                                        type="text" 
                                        required
                                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                        placeholder="Enter Server ID"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm mb-2">Notes (Optional)</label>
                                <textarea 
                                    v-model="form.buyer_note"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                    placeholder="Additional notes for your order..."
                                />
                            </div>
                            
                            <button 
                                type="submit"
                                :disabled="processing"
                                class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all disabled:opacity-50"
                            >
                                <span v-if="!processing">Buy Now - {{ formatCurrency(selectedProduct.price) }}</span>
                                <span v-else>Processing...</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Instructions -->
                    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">How to Top Up</h3>
                        <ol class="space-y-2 text-sm text-gray-300">
                            <li class="flex items-start space-x-2">
                                <span class="w-5 h-5 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                <span>Enter your User ID</span>
                            </li>
                            <li v-if="requiresServer" class="flex items-start space-x-2">
                                <span class="w-5 h-5 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                <span>Enter your Server ID</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="w-5 h-5 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">{{ requiresServer ? '3' : '2' }}</span>
                                <span>Select top up amount</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="w-5 h-5 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">{{ requiresServer ? '4' : '3' }}</span>
                                <span>Choose payment method</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="w-5 h-5 bg-accent-primary text-zinc-900 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">{{ requiresServer ? '5' : '4' }}</span>
                                <span>Complete payment</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Testimonials -->
                    <div v-if="testimonials.data.length > 0" class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                        <h3 class="text-lg font-semibold text-white mb-4">Customer Reviews</h3>
                        
                        <div class="space-y-4">
                            <div v-for="testimonial in testimonials.data.slice(0, 3)" :key="testimonial.id"
                                class="p-3 bg-zinc-800/50 rounded-xl">
                                <div class="flex items-center mb-2">
                                    <img :src="testimonial.user?.avatar_url || 'https://ui-avatars.com/api/?name=Guest'" 
                                        alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ testimonial.user?.name || testimonial.name || 'Anonymous' }}</p>
                                        <div class="flex items-center">
                                            <svg v-for="i in 5" :key="i" 
                                                :class="['w-3 h-3', i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-600']"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-300 text-sm">{{ testimonial.comment }}</p>
                            </div>
                        </div>
                        
                        <Link v-if="testimonials.data.length > 3" 
                            :href="`${route('games.show', game.slug)}#testimonials`"
                            class="block mt-4 text-accent-primary hover:text-accent-secondary text-sm text-center">
                            View All Reviews →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Related Games -->
            <div v-if="relatedGames.length > 0" class="mt-12">
                <h2 class="text-2xl font-bold text-white mb-6">Similar Games</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <GameCard v-for="relatedGame in relatedGames" :key="relatedGame.id" :game="relatedGame" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameCard from '@/Components/GameCard.vue';

const props = defineProps({
    game: Object,
    testimonials: Object,
    relatedGames: Array,
});

const selectedProduct = ref(null);
const processing = ref(false);

const form = ref({
    player_uid: '',
    player_server: '',
    buyer_note: '',
});

const requiresServer = computed(() => {
    // Games that require server ID
    const serverRequiredGames = ['mobile-legends', 'free-fire', 'pubg-mobile'];
    return serverRequiredGames.includes(props.game.slug);
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};

const submitOrder = () => {
    if (!selectedProduct.value) return;
    
    processing.value = true;
    
    const orderData = {
        game_id: props.game.id,
        product_id: selectedProduct.value.id,
        player_uid: form.value.player_uid,
        player_server: form.value.player_server,
        buyer_note: form.value.buyer_note,
    };
    
    router.post(route('checkout.process'), orderData, {
        onSuccess: () => {
            // Will redirect to payment page
        },
        onError: (errors) => {
            console.error('Checkout error:', errors);
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>