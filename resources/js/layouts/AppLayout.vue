<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'

const logout = () => {
    router.post('/logout')
}
</script>

<template>
    <div class="min-h-screen bg-background">
        <div class="flex min-h-screen">

            <aside class="flex w-64 flex-col border-r bg-card">

                <!-- Logo / Brand -->
                <div class="border-b px-6 py-5">
                    <h1 class="text-xl font-bold tracking-tight">
                        CRM
                    </h1>

                    <p class="mt-1 text-xs text-muted-foreground">
                        Customer Relationship Management
                    </p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-4 py-5">

                    <Link
                        href="/"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url === '/' ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Dashboard
                    </Link>

                    <Link
                        href="/clients"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url.startsWith('/clients') ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Clients
                    </Link>

                    <Link
                        href="/projects"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url.startsWith('/projects') ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Projects
                    </Link>

                    <Link
                        href="/tasks"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url.startsWith('/tasks') ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Tasks
                    </Link>

                    <Link
                        v-if="$page.props.auth.user.role === 'admin'"
                        href="/users"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url.startsWith('/users') ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Users
                    </Link>

                    <Link
                        href="/profile"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-muted"
                        :class="$page.url.startsWith('/profile') ? 'bg-primary/10 text-primary' : 'text-muted-foreground'"
                    >
                        Profile
                    </Link>

                </nav>

                <!-- User / Logout -->
                <div class="border-t p-4">

                    <div class="mb-3 rounded-lg bg-muted/50 px-3 py-3">
                        <p class="truncate text-sm font-semibold">
                            {{ $page.props.auth.user.name }}
                        </p>

                        <p class="truncate text-xs text-muted-foreground mt-1 ">
                            {{ $page.props.auth.user.role }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                        @click="logout"
                    >
                        Logout
                    </button>

                </div>

            </aside>

            <main class="min-w-0 flex-1">
                <slot />
            </main>

        </div>
    </div>
</template>