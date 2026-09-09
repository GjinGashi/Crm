<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

interface Project {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
}

interface Task {
    id: number;
    project_id: number;
    user_id: number;
    title: string;
    description: string | null;
    status: string;
    priority: string;
    start_time: string | null;
    end_time: string | null;
    due_date: string | null;
}

const projects = ref<Project[]>([]);
const users = ref<User[]>([]);
const task = ref<Task | null>(null);
const props = defineProps<{
    taskId: number;
}>();

const form = ref({
    project_id: null as number | null,
    user_id: null as number | null,
    title: '',
    description: '',
    status: 'Todo',
    priority: 'Low',
    start_time: '',
    end_time: '',
    due_date: '',
});

const isLoading = ref(true);
const isSaving = ref(false);
const error = ref('');

async function fetchProjects() {
    try {
        const response = await fetch('/api/projects');

        if (!response.ok) {
            throw new Error();
        }

        projects.value = await response.json();
    } catch {
        error.value = 'Unable to load projects. Please try again.';
    }
}

async function fetchUsers() {
    try {
        const response = await fetch('/api/users');

        if (!response.ok) {
            throw new Error();
        }

        users.value = await response.json();
    } catch {
        error.value = 'Unable to load users. Please try again.';
    }
}

async function fetchTask() {
    isLoading.value = true;
    error.value = '';

    try {
        const response = await fetch(`/api/tasks/${props.taskId}`);

        if (!response.ok) {
            throw new Error();
        }

        task.value = await response.json();

        if (!task.value) {
            throw new Error();
        }

        form.value = {
            project_id: task.value.project_id,
            user_id: task.value.user_id,
            title: task.value.title,
            description: task.value.description ?? '',
            status: task.value.status,
            priority: task.value.priority,
            start_time: task.value.start_time ?? '',
            end_time: task.value.end_time ?? '',
            due_date: task.value.due_date ?? '',
        };
    } catch {
        error.value = 'Unable to load task. Please try again.';
    } finally {
        isLoading.value = false;
    }
}

async function updateTask() {
    isSaving.value = true;
    error.value = '';

    if (
        form.value.start_time &&
        form.value.end_time &&
        form.value.end_time < form.value.start_time
    ) {
        error.value = 'End time cannot be before start time.';
        isSaving.value = false;
        return;
    }

    try {
        const response = await fetch(`/api/tasks/${props.taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(form.value),
        });

        if (!response.ok) {
            const data = await response.json();

            error.value =
                data.message || 'Unable to update task. Please try again.';

            return;
        }

        router.visit('/tasks');
    } catch {
        error.value = 'Unable to update task. Please try again.';
    } finally {
        isSaving.value = false;
    }
}

onMounted(() => {
    fetchTask();
    fetchProjects();
    fetchUsers();
});
</script>

<template>
    <Head title="Edit Task" />

    <AppLayout>
        <div class="space-y-6 p-6">
            <div>
                <Link href="/tasks">
                    <Button variant="outline"> Back to Tasks </Button>
                </Link>

                <h1 class="mt-2 text-3xl font-bold tracking-tight">
                    Edit Task
                </h1>

                <p class="text-muted-foreground">
                    Update the task information.
                </p>
            </div>

            <div
                v-if="isLoading"
                class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <p class="text-muted-foreground">Loading task...</p>
            </div>

            <div
                v-else-if="error && !task"
                class="max-w-3xl rounded-xl border border-red-200 bg-red-50 p-6 shadow-sm"
            >
                <p class="text-sm text-red-700">
                    {{ error }}
                </p>
            </div>

            <div
                v-else
                class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <form @submit.prevent="updateTask" class="space-y-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Task Title
                            <span class="text-destructive">*</span>
                        </label>

                        <Input
                            v-model="form.title"
                            placeholder="Enter task title"
                            required
                        />
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Project
                                <span class="text-destructive">*</span>
                            </label>

                            <select
                                v-model="form.project_id"
                                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                                required
                            >
                                <option :value="null">Select Project</option>

                                <option
                                    v-for="project in projects"
                                    :key="project.id"
                                    :value="project.id"
                                >
                                    {{ project.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Assigned User
                                <span class="text-destructive">*</span>
                            </label>

                            <select
                                v-model="form.user_id"
                                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                                required
                            >
                                <option :value="null">Select User</option>

                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Description
                        </label>

                        <Textarea
                            v-model="form.description"
                            placeholder="Describe the task..."
                        />
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Status
                            </label>

                            <select
                                v-model="form.status"
                                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="Todo">Todo</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Canceled">Canceled</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Priority
                            </label>

                            <select
                                v-model="form.priority"
                                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Start Time
                            </label>

                            <Input
                                v-model="form.start_time"
                                type="datetime-local"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                End Time
                            </label>

                            <Input
                                v-model="form.end_time"
                                type="datetime-local"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Due Date
                        </label>

                        <Input v-model="form.due_date" type="date" />
                    </div>

                    <div
                        v-if="error"
                        class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                    >
                        {{ error }}
                    </div>

                    <div class="flex gap-3 pt-2">
                        <Button type="submit" :disabled="isSaving">
                            {{ isSaving ? 'Updating...' : 'Update Task' }}
                        </Button>

                        <Link href="/tasks">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
