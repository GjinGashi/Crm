<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="bg-background min-h-screen">
        <div class="flex min-h-screen">
            <aside class="bg-card flex w-64 flex-col border-r">
                <!-- Logo / Brand -->
                <div class="border-b px-6 py-5">
                    <h1 class="text-xl font-bold tracking-tight">CRM</h1>

                    <p class="text-muted-foreground mt-1 text-xs">
                        Customer Relationship Management
                    </p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-4 py-5">
                    <Link
                        href="/"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url === '/'
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Dashboard
                    </Link>

                    <Link
                        href="/clients"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url.startsWith('/clients')
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Clients
                    </Link>

                    <Link
                        href="/projects"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url.startsWith('/projects')
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Projects
                    </Link>

                    <Link
                        href="/tasks"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url.startsWith('/tasks')
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Tasks
                    </Link>

                    <Link
                        v-if="$page.props.auth.user.role === 'admin'"
                        href="/users"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url.startsWith('/users')
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Users
                    </Link>

                    <Link
                        href="/profile"
                        class="hover:bg-muted flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                        :class="
                            $page.url.startsWith('/profile')
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground'
                        "
                    >
                        Profile
                    </Link>
                </nav>

                <!-- User / Logout -->
                <div class="border-t p-4">
                    <div class="bg-muted/50 mb-3 rounded-lg px-3 py-3">
                        <p class="truncate text-sm font-semibold">
                            {{ $page.props.auth.user.name }}
                        </p>

                        <p class="text-muted-foreground mt-1 truncate text-xs">
                            {{ $page.props.auth.user.role }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="text-muted-foreground hover:bg-destructive/10 hover:text-destructive w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium transition-colors"
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
