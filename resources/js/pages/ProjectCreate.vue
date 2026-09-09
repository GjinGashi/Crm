<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

interface Client {
    id: number;
    name: string;
}

const clients = ref<Client[]>([]);

const form = ref({
    client_id: null as number | null,
    name: '',
    description: '',
    status: 'Planning',
    priority: 'Low',
    start_date: '',
    due_date: '',
    budget: '' as string | number,
});

const isLoading = ref(false);
const error = ref('');

async function fetchClients() {
    try {
        const response = await fetch('/api/clients');

        if (!response.ok) {
            throw new Error();
        }

        clients.value = await response.json();
    } catch {
        error.value = 'Unable to load clients. Please try again.';
    }
}

async function createProject() {
    isLoading.value = true;
    error.value = '';

    try {
        const response = await fetch('/api/projects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(form.value),
        });

        if (!response.ok) {
            const data = await response.json();
            error.value =
                data.message || 'Unable to create project. Please try again.';
            return;
        }

        router.visit('/projects');
    } catch {
        error.value = 'Unable to create project. Please try again.';
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    fetchClients();
});
</script>

<template>
    <Head title="Create Project" />

    <AppLayout>
        <div class="space-y-6 p-6">
            <div>
                <Link href="/projects">
                    <Button variant="outline"> Back to Projects </Button>
                </Link>

                <h1 class="mt-2 text-3xl font-bold tracking-tight">
                    Create Project
                </h1>

                <p class="text-muted-foreground">
                    Create a new project and assign it to a client.
                </p>
            </div>

            <div
                class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <form @submit.prevent="createProject" class="space-y-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Project Name
                            <span class="text-destructive">*</span>
                        </label>

                        <Input
                            v-model="form.name"
                            placeholder="Enter project name"
                            required
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Client
                            <span class="text-destructive">*</span>
                        </label>

                        <select
                            v-model="form.client_id"
                            class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                            required
                        >
                            <option :value="null">Select Client</option>

                            <option
                                v-for="client in clients"
                                :key="client.id"
                                :value="client.id"
                            >
                                {{ client.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Description
                        </label>

                        <Textarea
                            v-model="form.description"
                            placeholder="Describe the project..."
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
                                <option value="Planning">Planning</option>
                                <option value="In Progress">In Progress</option>
                                <option value="On Hold">On Hold</option>
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
                                Start Date
                            </label>

                            <Input v-model="form.start_date" type="date" />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">
                                Due Date
                            </label>

                            <Input v-model="form.due_date" type="date" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">
                            Budget
                        </label>

                        <Input
                            v-model="form.budget"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        />
                    </div>

                    <div
                        v-if="error"
                        class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                    >
                        {{ error }}
                    </div>

                    <div class="flex gap-3 pt-2">
                        <Button type="submit" :disabled="isLoading">
                            {{ isLoading ? 'Creating...' : 'Create Project' }}
                        </Button>

                        <Link href="/projects">
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
