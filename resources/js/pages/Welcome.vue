<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Users, FolderKanban, CheckSquare } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
interface Props {
    clientsCount: number;
    projectsCount: number;
    tasksCount: number;
    planningProjects: number;
    inProgressProjects: number;
    completedProjects: number;
    CanceledProjects: number;

    todoTasks: number;
    inProgressTasks: number;
    completedTasks: number;
    CanceledTasks: number;
}
defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />
        <div class="min-h-screen bg-slate-50 px-6 py-10 lg:px-8">
            <div class="mx-auto max-w-7xl space-y-10">
                <div>
                    <p class="text-lg font-semibold tracking-tight">
                        Welcome back, {{ $page.props.auth.user.name }}.
                    </p>
                </div>
                <div class="grid gap-5 pt-2 md:grid-cols-3">
                    <Card
                        class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <CardContent class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p
                                        class="text-muted-foreground text-sm font-medium"
                                    >
                                        Total Clients
                                    </p>

                                    <p
                                        class="mt-3 text-3xl font-bold tracking-tight"
                                    >
                                        {{ clientsCount }}
                                    </p>

                                    <p
                                        class="text-muted-foreground mt-1 text-xs"
                                    >
                                        Clients in your CRM
                                    </p>
                                </div>

                                <div class="rounded-xl bg-blue-50 p-3">
                                    <Users class="h-5 w-5 text-blue-600" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card
                        class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <CardContent class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p
                                        class="text-muted-foreground text-sm font-medium"
                                    >
                                        Total Projects
                                    </p>

                                    <p
                                        class="mt-3 text-3xl font-bold tracking-tight"
                                    >
                                        {{ projectsCount }}
                                    </p>

                                    <p
                                        class="text-muted-foreground mt-1 text-xs"
                                    >
                                        Projects across all clients
                                    </p>
                                </div>

                                <div class="rounded-xl bg-violet-50 p-3">
                                    <FolderKanban
                                        class="h-5 w-5 text-violet-600"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card
                        class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <CardContent class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p
                                        class="text-muted-foreground text-sm font-medium"
                                    >
                                        Total Tasks
                                    </p>

                                    <p
                                        class="mt-3 text-3xl font-bold tracking-tight"
                                    >
                                        {{ tasksCount }}
                                    </p>

                                    <p
                                        class="text-muted-foreground mt-1 text-xs"
                                    >
                                        Tasks across all projects
                                    </p>
                                </div>

                                <div class="rounded-xl bg-emerald-50 p-3">
                                    <CheckSquare
                                        class="h-5 w-5 text-emerald-600"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div class="grid gap-6 lg:grid-cols-2">
                    <!-- Project Status -->
                    <Card class="border-border/60 bg-white shadow-sm">
                        <CardHeader class="border-b px-6 py-5">
                            <CardTitle class="text-base font-semibold">
                                Project Status
                            </CardTitle>

                            <p class="text-muted-foreground text-sm">
                                Overview of your current projects
                            </p>
                        </CardHeader>

                        <CardContent class="p-0">
                            <div class="divide-y">
                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-blue-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Planning
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ planningProjects }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-amber-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            In Progress
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ inProgressProjects }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-emerald-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Completed
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ completedProjects }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-red-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Canceled
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ CanceledProjects }}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Task Status -->
                    <Card class="border-border/60 bg-white shadow-sm">
                        <CardHeader class="border-b px-6 py-5">
                            <CardTitle class="text-base font-semibold">
                                Task Status
                            </CardTitle>

                            <p class="text-muted-foreground text-sm">
                                Overview of your current tasks
                            </p>
                        </CardHeader>

                        <CardContent class="p-0">
                            <div class="divide-y">
                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-blue-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Todo
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ todoTasks }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-amber-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            In Progress
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ inProgressTasks }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-emerald-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Completed
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ completedTasks }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between px-6 py-4"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-red-500"
                                        ></span>

                                        <span class="text-sm font-medium">
                                            Canceled
                                        </span>
                                    </div>

                                    <span class="text-lg font-semibold">
                                        {{ CanceledTasks }}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
