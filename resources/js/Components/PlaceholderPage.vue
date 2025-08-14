<template>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-zinc-900/60 rounded-2xl p-12 ring-1 ring-white/10 text-center">
            <h1 class="text-3xl font-bold text-white mb-4">{{ title }}</h1>
            <p v-if="subtitle" class="text-gray-400">{{ subtitle }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    subtitle: String,
});
</script>