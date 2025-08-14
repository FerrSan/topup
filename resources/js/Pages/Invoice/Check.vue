<template>
    <AppLayout title="Check Invoice">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-md mx-auto">
                <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white mb-2">Check Invoice</h1>
                        <p class="text-gray-400">Enter your invoice number to track your order</p>
                    </div>

                    <form @submit.prevent="searchInvoice">
                        <div class="mb-6">
                            <label class="block text-gray-300 text-sm mb-2">Invoice Number</label>
                            <input 
                                v-model="invoiceNo" 
                                type="text" 
                                required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                placeholder="e.g. INV20240101ABC123"
                            >
                        </div>

                        <button type="submit" class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                            Check Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const invoiceNo = ref('');

const searchInvoice = () => {
    router.post(route('invoice.search'), {
        invoice_no: invoiceNo.value
    });
};
</script>