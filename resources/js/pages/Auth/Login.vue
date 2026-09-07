<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
const page = usePage();
const email = ref('');
const password = ref('');
const login = () => {
    router.post('/login', {
        email: email.value,
        password: password.value,
    });
};
</script>
<template>
    <div
        class="bg-background flex min-h-screen items-center justify-center p-6"
    >
        <div class="bg-card w-full max-w-md rounded-xl border p-8 shadow-sm">
            <h1 class="mb-2 text-2xl font-bold">Login</h1>
            <p class="text-muted-foreground mb-6 text-sm">
                Sign In To Your Crm Account
            </p>
            <form novalidate @submit.prevent="login" class="space-y-5">
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input
                    v-model="email"
                    type="email"
                    class="bg-background focus:ring-ring w-full rounded-lg border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                />
                <p
                    v-if="page.props.errors.email"
                    class="text-destructive mt-1 text-sm"
                >
                    {{ page.props.errors.email }}
                </p>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input
                    v-model="password"
                    type="password"
                    class="bg-background focus:ring-ring w-full rounded-lg border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                />
                <p
                    v-if="page.props.errors.password"
                    class="text-destructive mt-1 text-sm"
                >
                    {{ page.props.errors.password }}
                </p>
                <button
                    type="submit"
                    class="bg-primary text-primary-foreground w-full rounded-lg px-4 py-2.5 text-sm font-semibold transition hover:opacity-90"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</template>
