<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
interface Project {
    id: number;
    name: string;
    description: string | null;
    status: string;
    priority: string;
}
interface Client {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    company: string | null;
    address: string | null;
    city: string | null;
    country: string | null;
    status: string;
    notes: string | null;
    projects: Project[];
}
const client = ref<Client | null>(null);
const error = ref('');
onMounted(async () => {
    const clientId = window.location.pathname.split('/').pop();

    try {
        const response = await fetch(`/api/clients/${clientId}`);

        if (!response.ok) {
            throw new Error('Unable to load client.');
        }

        client.value = await response.json();
    } catch {
        error.value = 'Unable to load client. Please try again.';
    }
});
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Client Details
                    </h1>
                    <p class="text-muted-foreground">
                        View client information and related projects
                    </p>
                </div>

                <Link href="/clients">
                    <Button variant="outline"> Back to Clients </Button>
                </Link>
            </div>

            <div v-if="client" class="space-y-6">
                <div class="space-y-4 rounded-lg border p-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-semibold">
                            {{ client.name }}
                        </h2>

                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                            :class="{
                                'bg-emerald-50 text-emerald-700':
                                    client.status === 'Active',
                                'bg-slate-100 text-slate-600':
                                    client.status === 'Inactive',
                                'bg-blue-50 text-blue-700':
                                    client.status === 'Lead',
                                'bg-amber-50 text-amber-700':
                                    client.status === 'Archived',
                            }"
                        >
                            {{ client.status }}
                        </span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Email</p>
                            <p class="font-medium">{{ client.email }}</p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Phone</p>
                            <p class="font-medium">{{ client.phone || '-' }}</p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Company</p>
                            <p class="font-medium">
                                {{ client.company || '-' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Address</p>
                            <p class="font-medium">
                                {{ client.address || '-' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">City</p>
                            <p class="font-medium">{{ client.city || '-' }}</p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Country</p>
                            <p class="font-medium">
                                {{ client.country || '-' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-muted-foreground text-sm">Notes</p>
                            <p class="font-medium">{{ client.notes || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 rounded-lg border p-6">
                    <h2 class="text-xl font-semibold">Projects</h2>

                    <div v-if="client.projects.length === 0">
                        <p class="text-muted-foreground">
                            No projects associated with this client yet.
                        </p>
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="project in client.projects"
                            :key="project.id"
                            class="rounded-lg border bg-white p-4 shadow-sm"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">
                                    {{ project.name }}
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-slate-100 text-slate-700':
                                            project.status === 'Planning',
                                        'bg-blue-50 text-blue-700':
                                            project.status === 'In Progress',
                                        'bg-amber-50 text-amber-700':
                                            project.status === 'On Hold',
                                        'bg-emerald-50 text-emerald-700':
                                            project.status === 'Completed',
                                        'bg-red-50 text-red-700':
                                            project.status === 'Canceled',
                                    }"
                                >
                                    {{ project.status }}
                                </span>
                            </div>

                            <p class="text-muted-foreground text-sm">
                                {{ project.description || 'No description' }}
                            </p>

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="{
                                    'bg-slate-100 text-slate-700':
                                        project.priority === 'Low',
                                    'bg-blue-50 text-blue-700':
                                        project.priority === 'Medium',
                                    'bg-orange-50 text-orange-700':
                                        project.priority === 'High',
                                    'bg-red-50 text-red-700':
                                        project.priority === 'Urgent',
                                }"
                            >
                                {{ project.priority }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else-if="error">
                <p class="text-destructive">{{ error }}</p>
            </div>

            <div v-else>
                <p class="text-muted-foreground">Loading client...</p>
            </div>
        </div>
    </AppLayout>
</template>
