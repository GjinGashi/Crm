```vue
<script setup lang="ts">
import { onMounted, ref } from 'vue'
import axios from 'axios'

import { Users, FolderKanban, CheckSquare } from 'lucide-vue-next'
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

import api from '@/lib/api'

interface User {
    id: number
    first_name: string
    last_name: string
    email: string
    role: string
}

interface DashboardData {
    user: User
    clientsCount: number
    projectsCount: number
    tasksCount: number
    planningProjects: number
    inProgressProjects: number
    completedProjects: number
    CanceledProjects: number
    todoTasks: number
    inProgressTasks: number
    completedTasks: number
    CanceledTasks: number
}

const dashboard = ref<DashboardData | null>(null)
const isLoading = ref(true)
const error = ref('')

async function fetchDashboard() {
    try {
        const response = await api.get('/dashboard')

        dashboard.value = response.data
    } catch (err: unknown) {
        if (axios.isAxiosError(err)) {
            error.value =
                err.response?.data?.message ??
                'Unable to load dashboard.'
        } else {
            error.value = 'Unable to load dashboard.'
        }
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchDashboard)
</script>

<template>
    <main class="min-h-screen bg-slate-50 px-6 py-10 lg:px-8">
        <div v-if="isLoading" class="mx-auto max-w-7xl">
            <p class="text-muted-foreground">Loading dashboard...</p>
        </div>

        <div v-else-if="error" class="mx-auto max-w-7xl">
            <p class="text-destructive">{{ error }}</p>
        </div>

        <div v-else-if="dashboard" class="mx-auto max-w-7xl space-y-10">
            <div>
                <p class="text-lg font-semibold tracking-tight">
                    Welcome back, {{ dashboard.user.first_name }} {{ dashboard.user.last_name }}.
                </p>
            </div>

            <div class="grid gap-5 pt-2 md:grid-cols-3">
                <Card
                    class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <CardContent class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-muted-foreground text-sm font-medium">
                                    Total Clients
                                </p>

                                <p class="mt-3 text-3xl font-bold tracking-tight">
                                    {{ dashboard.clientsCount }}
                                </p>

                                <p class="text-muted-foreground mt-1 text-xs">
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
                    class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <CardContent class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-muted-foreground text-sm font-medium">
                                    Total Projects
                                </p>

                                <p class="mt-3 text-3xl font-bold tracking-tight">
                                    {{ dashboard.projectsCount }}
                                </p>

                                <p class="text-muted-foreground mt-1 text-xs">
                                    Projects across all clients
                                </p>
                            </div>

                            <div class="rounded-xl bg-violet-50 p-3">
                                <FolderKanban class="h-5 w-5 text-violet-600" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card
                    class="border-border/60 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <CardContent class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-muted-foreground text-sm font-medium">
                                    Total Tasks
                                </p>

                                <p class="mt-3 text-3xl font-bold tracking-tight">
                                    {{ dashboard.tasksCount }}
                                </p>

                                <p class="text-muted-foreground mt-1 text-xs">
                                    Tasks across all projects
                                </p>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-3">
                                <CheckSquare class="h-5 w-5 text-emerald-600" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
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
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>

                                    <span class="text-sm font-medium">
                                        Planning
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.planningProjects }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>

                                    <span class="text-sm font-medium">
                                        In Progress
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.inProgressProjects }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>

                                    <span class="text-sm font-medium">
                                        Completed
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.completedProjects }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>

                                    <span class="text-sm font-medium">
                                        Canceled
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.CanceledProjects }}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

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
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>

                                    <span class="text-sm font-medium">
                                        Todo
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.todoTasks }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>

                                    <span class="text-sm font-medium">
                                        In Progress
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.inProgressTasks }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>

                                    <span class="text-sm font-medium">
                                        Completed
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.completedTasks }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>

                                    <span class="text-sm font-medium">
                                        Canceled
                                    </span>
                                </div>

                                <span class="text-lg font-semibold">
                                    {{ dashboard.CanceledTasks }}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </main>
</template>
