<template>
    <GuestLayout title="Login">
        <div class="max-w-md mx-auto">
            <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
                    <p class="text-gray-400 mt-2">Login to your account</p>
                </div>

                <div v-if="status" class="mb-4 p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                    <p class="text-green-500 text-sm">{{ status }}</p>
                </div>

                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div class="mb-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="form.remember" />
                            <span class="ml-2 text-gray-300 text-sm">Remember me</span>
                        </label>
                        
                        <Link
                            v-if="canResetPassword"
                            :href="$route('password.request')"
                            class="text-accent-primary hover:text-accent-secondary text-sm"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <PrimaryButton class="w-full" :disabled="form.processing">
                        Login
                    </PrimaryButton>
                </form>

                <!-- Register Link -->
                <p class="mt-8 text-center text-gray-400">
                    Don't have an account? 
                    <Link :href="$route('register')" class="text-accent-primary hover:text-accent-secondary font-semibold">
                        Sign up
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Helper function untuk route
const page = usePage();
const $route = (name, params = {}) => {
    if (typeof route === 'function') {
        return route(name, params);
    }
    // Fallback jika route helper tidak tersedia
    const routes = {
        'login': '/login',
        'register': '/register',
        'password.request': '/forgot-password',
        'dashboard': '/dashboard',
        'admin.dashboard': '/admin/dashboard',
    };
    return routes[name] || '/';
};

const submit = () => {
    form.post($route('login'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            // Redirect berdasarkan role
            if (page.props.auth?.user?.roles?.includes('admin')) {
                window.location.href = '/admin/dashboard';
            }
        },
    });
};
</script>