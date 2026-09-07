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
onMounted(async () => {
    const clientId = window.location.pathname.split('/').pop();
    const response = await fetch(`/api/clients/${clientId}`);
    client.value = await response.json();
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
                    <h2 class="text-xl font-semibold">
                        {{ client.name }}
                    </h2>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-muted-foreground text-sm">Email</p>
                            <p>{{ client.email }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Phone</p>
                            <p>{{ client.phone || '-' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Company</p>
                            <p>{{ client.company || '-' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Address</p>
                            <p>{{ client.address || '-' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">City</p>
                            <p>{{ client.city || '-' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Country</p>
                            <p>{{ client.country || '-' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Status</p>
                            <p>{{ client.status }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Notes</p>
                            <p>{{ client.notes || '-' }}</p>
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
                            class="rounded-md border p-4"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">
                                    {{ project.name }}
                                </h3>

                                <span class="text-sm">
                                    {{ project.status }}
                                </span>
                            </div>

                            <p class="text-muted-foreground text-sm">
                                {{ project.description || 'No description' }}
                            </p>

                            <p class="text-sm">
                                Priority: {{ project.priority }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <p class="text-muted-foreground">Loading client...</p>
            </div>
        </div>
    </AppLayout>
</template>
