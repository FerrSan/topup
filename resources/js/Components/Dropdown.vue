<template>
    <div class="relative" v-click-outside="closeDropdown">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute right-0 z-50 mt-2 w-48 rounded-xl shadow-lg bg-zinc-900 ring-1 ring-white/10"
                style="display: none"
            >
                <div class="rounded-xl ring-1 ring-black ring-opacity-5 py-1 bg-zinc-900">
                    <slot name="content" />
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const open = ref(false);

const closeDropdown = () => {
    open.value = false;
};

const escape = (e) => {
    if (e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', escape));
onUnmounted(() => document.removeEventListener('keydown', escape));

// Click outside directive
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    },
};
</script>