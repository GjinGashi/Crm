<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

interface Client {
    id: number;
    name: string;
}

interface Task {
    id: number;
    title: string;
    description: string | null;
    status: string;
    priority: string;
}

interface Project {
    id: number;
    name: string;
    description: string | null;
    status: string;
    priority: string;
    start_date: string | null;
    due_date: string | null;
    budget: number | null;
    client: Client;
    tasks: Task[];
}

const project = ref<Project | null>(null);

onMounted(async () => {
    const projectId = window.location.pathname.split('/').pop();

    const response = await fetch(`/api/projects/${projectId}`);

    project.value = await response.json();
});
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Project Details
                    </h1>

                    <p class="text-muted-foreground">
                        View project information and related tasks
                    </p>
                </div>

                <Link href="/projects">
                    <Button variant="outline"> Back to Projects </Button>
                </Link>
            </div>

            <div v-if="project" class="space-y-6">
                <div class="space-y-4 rounded-lg border p-6">
                    <h2 class="text-xl font-semibold">
                        {{ project.name }}
                    </h2>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-muted-foreground text-sm">Client</p>

                            <p>
                                {{ project.client?.name || '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Status</p>

                            <p>
                                {{ project.status }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">
                                Priority
                            </p>

                            <p>
                                {{ project.priority }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Budget</p>

                            <p>
                                {{ project.budget ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">
                                Start Date
                            </p>

                            <p>
                                {{ project.start_date || '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">
                                Due Date
                            </p>

                            <p>
                                {{ project.due_date || '-' }}
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-muted-foreground text-sm">
                                Description
                            </p>

                            <p>
                                {{ project.description || '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 rounded-lg border p-6">
                    <h2 class="text-xl font-semibold">Tasks</h2>

                    <div v-if="project.tasks.length === 0">
                        <p class="text-muted-foreground">
                            No tasks associated with this project yet.
                        </p>
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="task in project.tasks"
                            :key="task.id"
                            class="rounded-md border p-4"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">
                                    {{ task.title }}
                                </h3>

                                <span class="text-sm">
                                    {{ task.status }}
                                </span>
                            </div>

                            <p class="text-muted-foreground text-sm">
                                {{ task.description || 'No description' }}
                            </p>

                            <p class="text-sm">Priority: {{ task.priority }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <p class="text-muted-foreground">Loading project...</p>
            </div>
        </div>
    </AppLayout>
</template>
