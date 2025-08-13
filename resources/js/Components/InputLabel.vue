<template>
    <label class="block text-gray-300 text-sm mb-2">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="required" class="text-red-500 ml-1">*</span>
    </label>
</template>

<script setup>
defineProps({
    value: String,
    required: Boolean,
});
</script>