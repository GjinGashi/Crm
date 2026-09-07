<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import AppLayout from '@/layouts/AppLayout.vue'

interface Project {
    id: number
    name: string
}

interface User {
    id: number
    name: string
}

interface Task {
    id: number
    title: string
    description: string | null
    status: string
    priority: string
    start_time: string | null
    end_time: string | null
    due_date: string | null
    project: Project
    user: User
}

const task = ref<Task | null>(null)

onMounted(async () => {
    const taskId = window.location.pathname.split('/').pop()

    const response = await fetch(`/api/tasks/${taskId}`)

    task.value = await response.json()
})
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Task Details
                    </h1>

                    <p class="text-muted-foreground">
                        View task information
                    </p>
                </div>

                <Link href="/tasks">
                    <Button variant="outline">
                        Back to Tasks
                    </Button>
                </Link>
            </div>

            <div v-if="task" class="rounded-lg border p-6 space-y-6">

                <div>
                    <h2 class="text-xl font-semibold">
                        {{ task.title }}
                    </h2>
                </div>

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Project
                        </p>

                        <p>
                            {{ task.project?.name || '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Assigned To
                        </p>

                        <p>
                            {{ task.user?.name || '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Status
                        </p>

                        <p>
                            {{ task.status }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Priority
                        </p>

                        <p>
                            {{ task.priority }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Start Time
                        </p>

                        <p>
                            {{ task.start_time || '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            End Time
                        </p>

                        <p>
                            {{ task.end_time || '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            Due Date
                        </p>

                        <p>
                            {{ task.due_date || '-' }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-muted-foreground">
                            Description
                        </p>

                        <p>
                            {{ task.description || '-' }}
                        </p>
                    </div>

                </div>

            </div>

            <div v-else>
                <p class="text-muted-foreground">
                    Loading task...
                </p>
            </div>

        </div>
    </AppLayout>
</template>