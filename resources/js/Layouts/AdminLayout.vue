<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="bg-dark-bg text-gray-100 min-h-screen">
    <div class="flex">
      <aside class="w-60 min-h-screen bg-dark-surface border-r border-white/10 p-4 space-y-2">
        <h2 class="text-sm uppercase text-gray-400 mb-2">Admin</h2>
        <nav class="space-y-1">
          <Link :href="route('admin.dashboard')" class="block px-3 py-2 rounded hover:bg-white/5">Dashboard</Link>
          <Link :href="route('admin.games.index')" class="block px-3 py-2 rounded hover:bg-white/5">Games</Link>
          <Link :href="route('admin.products.index')" class="block px-3 py-2 rounded hover:bg-white/5">Products</Link>
          <Link :href="route('admin.orders.index')" class="block px-3 py-2 rounded hover:bg-white/5">Orders</Link>
          <Link :href="route('admin.webhooks.index')" class="block px-3 py-2 rounded hover:bg-white/5">Webhooks</Link>
        </nav>
      </aside>
      <main class="flex-1">
        <slot />
      </main>
    </div>
  </div>
</template>
