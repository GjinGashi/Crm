<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3'
const page = usePage()
const email = ref('')
const password = ref('')
const login = () => {
    router.post('/login', {
        email: email.value,
        password: password.value,
    })
}
</script>
<template>
    <div class="flex min-h-screen items-center justify-center bg-background p-6">
        <div class="w-full max-w-md rounded-xl border bg-card p-8 shadow-sm">
            <h1 class="mb-2 text-2xl font-bold">Login</h1>
            <p class="mb-6 text-sm text-muted-foreground">Sign In To Your Crm Account</p>
            <form novalidate @submit.prevent="login" class="space-y-5">
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input v-model="email" type="email" class="w-full rounded-lg border bg-background px-3 py-2.5 text-sm outline-none transition focus:ring-2 focus:ring-ring">
                <p v-if="page.props.errors.email" class=" mt-1 text-sm text-destructive">{{ page.props.errors.email }}</p>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input v-model="password" type="password" class="w-full rounded-lg border bg-background px-3 py-2.5 text-sm outline-none transition focus:ring-2 focus:ring-ring">
                <p v-if="page.props.errors.password" class="mt-1 text-sm text-destructive">{{ page.props.errors.password }}
                </p>
                <button type="submit"
                    class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:opacity-90">Login</button>
            </form>
        </div>
    </div>
</template>