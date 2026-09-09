<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Toaster } from '@/components/ui/sonner';
import {
    LayoutDashboard,
    Users,
    FolderKanban,
    CheckSquare,
    UserCircle,
    LogOut,
} from 'lucide-vue-next';
const sidebarOpen = ref(false);
const searchQuery = ref('');

interface SearchResults {
    clients: { id: number; name: string }[];
    projects: { id: number; name: string }[];
    tasks: { id: number; title: string }[];
}
const searchResults = ref<SearchResults>({
    clients: [],
    projects: [],
    tasks: [],
});

async function search() {
    if (!searchQuery.value.trim()) {
        searchResults.value = {
            clients: [],
            projects: [],
            tasks: [],
        };
        return;
    }

    const response = await fetch(
        `/api/search?q=${encodeURIComponent(searchQuery.value)}`,
    );

    if (response.ok) {
        searchResults.value = await response.json();
    }
}
const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="bg-background min-h-screen">
        <div class="flex min-h-screen">
            <aside
                class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-[#343a40] text-slate-100 transition-transform duration-200 md:sticky md:top-0 md:h-screen"
            >
                <!-- Logo / Brand -->
                <div class="flex items-center justify-between px-5 py-5">
                    <div class="flex items-center gap-3">
                        <img
                            src="/logo.png"
                            alt="CRM Logo"
                            class="h-11 w-auto shrink-0"
                        />
                    </div>

                    <button
                        type="button"
                        class="text-muted-foreground hover:bg-muted rounded-lg p-2 md:hidden"
                        @click="sidebarOpen = false"
                        aria-label="Close navigation"
                    >
                        ×
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-col space-y-6 px-4 pt-2">
                    <div>
                        <p
                            class="text-muted-foreground mb-2 px-3 text-xs font-semibold tracking-wider uppercase"
                        >
                            Main
                        </p>

                        <div class="space-y-1">
                            <Link
                                href="/"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url === '/'
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <LayoutDashboard class="h-4 w-4 shrink-0" />
                                <span>Dashboard</span>
                            </Link>

                            <Link
                                href="/clients"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url.startsWith('/clients')
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <Users class="h-4 w-4 shrink-0" />
                                <span>Clients</span>
                            </Link>

                            <Link
                                href="/projects"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url.startsWith('/projects')
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <FolderKanban class="h-4 w-4 shrink-0" />
                                <span>Projects</span>
                            </Link>

                            <Link
                                href="/tasks"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url.startsWith('/tasks')
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <CheckSquare class="h-4 w-4 shrink-0" />
                                <span>Tasks</span>
                            </Link>
                        </div>
                    </div>

                    <div v-if="$page.props.auth.user.role === 'admin'">
                        <p
                            class="text-muted-foreground mb-2 px-3 text-xs font-semibold tracking-wider uppercase"
                        >
                            Management
                        </p>

                        <div class="space-y-1">
                            <Link
                                href="/users"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url.startsWith('/users')
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <Users class="h-4 w-4 shrink-0" />
                                <span>Users</span>
                            </Link>
                        </div>
                    </div>

                    <div>
                        <p
                            class="text-muted-foreground mb-2 px-3 text-xs font-semibold tracking-wider uppercase"
                        >
                            Account
                        </p>

                        <div class="space-y-1">
                            <Link
                                href="/profile"
                                class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    $page.url.startsWith('/profile')
                                        ? 'bg-white/10 text-white ring-1 ring-white/10'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                "
                            >
                                <UserCircle class="h-4 w-4 shrink-0" />
                                <span>Profile</span>
                            </Link>
                        </div>
                    </div>

                    <!-- User -->
                    <div class="pt-2">
                        <div
                            class="mb-3 flex items-center gap-3 rounded-lg border border-white/10 bg-black/10 px-3 py-3"
                        >
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white ring-1 ring-white/10"
                            >
                                {{
                                    $page.props.auth.user.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold">
                                    {{ $page.props.auth.user.name }}
                                </p>

                                <p
                                    class="text-muted-foreground mt-0.5 truncate text-xs capitalize"
                                >
                                    {{ $page.props.auth.user.role }}
                                </p>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="mt-auto px-4 pb-4">
                    <button
                        type="button"
                        class="text-muted-foreground hover:bg-destructive/10 hover:text-destructive flex w-full items-center gap-3 rounded-lg px-0 py-3 text-left text-sm font-medium transition-colors"
                        @click="logout"
                    >
                        <LogOut class="h-4 w-4 shrink-0" />
                        <span>Logout</span>
                    </button>
                </div>
            </aside>

            <main class="min-w-0 flex-1">
                <header
                    class="flex h-16 items-center justify-between border-b bg-white px-6 lg:px-8"
                >
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="hover:bg-muted rounded-lg p-2 md:hidden"
                            @click="sidebarOpen = true"
                            aria-label="Open navigation"
                        >
                            ☰
                        </button>

                        <h1 class="text-sm font-semibold text-slate-900">
                            {{
                                $page.url === '/'
                                    ? 'Dashboard'
                                    : $page.url.startsWith('/clients/')
                                      ? 'Client Details'
                                      : $page.url.startsWith('/projects/')
                                        ? 'Project Details'
                                        : $page.url.startsWith('/tasks/')
                                          ? 'Task Details'
                                          : $page.url.startsWith('/clients')
                                            ? 'Clients'
                                            : $page.url.startsWith('/projects')
                                              ? 'Projects'
                                              : $page.url.startsWith('/tasks')
                                                ? 'Tasks'
                                                : $page.url.startsWith('/users')
                                                  ? 'Users'
                                                  : $page.url.startsWith(
                                                          '/profile',
                                                      )
                                                    ? 'Profile'
                                                    : ''
                            }}
                        </h1>
                    </div>

                    <div class="relative hidden w-full max-w-md md:block">
                        <input
                            v-model="searchQuery"
                            type="search"
                            @input="search"
                            placeholder="Search clients, projects, tasks..."
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm transition outline-none focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-200"
                        />

                        <div
                            v-if="
                                searchQuery.trim() &&
                                (searchResults.clients.length ||
                                    searchResults.projects.length ||
                                    searchResults.tasks.length)
                            "
                            class="absolute top-full right-0 left-0 z-50 mt-2 rounded-lg border border-slate-200 bg-white p-2 shadow-lg"
                        >
                            <div v-if="searchResults.clients.length">
                                <p
                                    class="px-3 py-2 text-xs font-semibold tracking-wide text-slate-500 uppercase"
                                >
                                    Clients
                                </p>

                                <Link
                                    v-for="client in searchResults.clients"
                                    :key="`client-${client.id}`"
                                    :href="`/clients/${client.id}`"
                                    class="block rounded-md px-3 py-2 text-sm hover:bg-slate-50"
                                >
                                    {{ client.name }}
                                </Link>
                            </div>

                            <div v-if="searchResults.projects.length">
                                <p
                                    class="px-3 py-2 text-xs font-semibold tracking-wide text-slate-500 uppercase"
                                >
                                    Projects
                                </p>

                                <Link
                                    v-for="project in searchResults.projects"
                                    :key="`project-${project.id}`"
                                    :href="`/projects/${project.id}`"
                                    class="block rounded-md px-3 py-2 text-sm hover:bg-slate-50"
                                >
                                    {{ project.name }}
                                </Link>
                            </div>

                            <div v-if="searchResults.tasks.length">
                                <p
                                    class="px-3 py-2 text-xs font-semibold tracking-wide text-slate-500 uppercase"
                                >
                                    Tasks
                                </p>

                                <Link
                                    v-for="task in searchResults.tasks"
                                    :key="`task-${task.id}`"
                                    :href="`/tasks/${task.id}`"
                                    class="block rounded-md px-3 py-2 text-sm hover:bg-slate-50"
                                >
                                    {{ task.title }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </header>

                <slot />
            </main>
        </div>
    </div>
    <Toaster
        position="top-center"
        :pause-on-hover="false"
        :toast-options="{
            class: 'w-[380px] rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-xl',
        }"
    />
</template>
