<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
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
}
async function createClient() {
    const response = await fetch('/api/clients', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    });
    const client = await response.json();
    clients.value.push(client);
    form.value = {
        name: '',
        email: '',
        phone: '',
        company: '',
        address: '',
        city: '',
        country: '',
        status: 'Active',
        notes: '',
    };
}

function editClient(client: Client) {
    editingClientId.value = client.id;
    form.value = {
        name: client.name,
        email: client.email,
        phone: client.phone ?? '',
        company: client.company ?? '',
        address: client.address ?? '',
        city: client.city ?? '',
        country: client.country ?? '',
        status: client.status,
        notes: client.notes ?? '',
    };
}
function cancelEdit() {
    editingClientId.value = null;
    form.value = {
        name: '',
        email: '',
        phone: '',
        company: '',
        address: '',
        city: '',
        country: '',
        status: 'Active',
        notes: '',
    };
}
async function updateClient() {
    if (editingClientId.value === null) return;
    const response = await fetch(`/api/clients/${editingClientId.value}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    });
    const updatedClient = await response.json();
    const index = clients.value.findIndex(
        (client) => client.id === editingClientId.value,
    );
    if (index !== -1) {
        clients.value[index] = updatedClient;
    }
    editingClientId.value = null;
    form.value = {
        name: '',
        email: '',
        phone: '',
        company: '',
        address: '',
        city: '',
        country: '',
        status: 'Active',
        notes: '',
    };
}
async function deleteClient(id: number) {
    const response = await fetch(`/api/clients/${id}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        const data = await response.json();
        alert(data.message);
        return;
    }

    clients.value = clients.value.filter((client) => client.id !== id);
}

const clients = ref<Client[]>([]);
const editingClientId = ref<number | null>(null);
const search = ref('');
const statusFilter = ref('All');

const filteredClients = computed(() => {
    return clients.value.filter((client) => {
        const matchesSearch = client.name
            .toLowerCase()
            .includes(search.value.toLowerCase());

        const matchesStatus =
            statusFilter.value === 'All' ||
            client.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});
const form = ref({
    name: '',
    email: '',
    phone: '',
    company: '',
    address: '',
    city: '',
    country: '',
    status: 'Active',
    notes: '',
});

onMounted(async () => {
    const response = await fetch('/api/clients');
    clients.value = await response.json();
});
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <h1 class="text-3xl font-bold tracking-tight">Clients</h1>
            <p class="text-muted-foreground">
                Manage your clients and their information
            </p>
            <Input v-model="search" placeholder="Search clients by name" />
            <select
                v-model="statusFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
            >
                <option value="All">Filter By Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Lead">Lead</option>
                <option value="Archived">Archived</option>
            </select>
            <form
                @submit.prevent="
                    editingClientId ? updateClient() : createClient()
                "
                class="space-y-4"
            >
                <Input v-model="form.name" placeholder="Name" />
                <Input v-model="form.email" placeholder="Email" />
                <Input v-model="form.phone" placeholder="Phone" />
                <Input v-model="form.company" placeholder="Company" />
                <Input v-model="form.address" placeholder="Address" />
                <Input v-model="form.city" placeholder="City" />
                <Input v-model="form.country" placeholder="Country" />

                <select
                    v-model="form.status"
                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-10 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                >
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Lead">Lead</option>
                    <option value="Archived">Archived</option>
                </select>
                <Textarea v-model="form.notes" placeholder="Notes"></Textarea>
                <div class="flex gap-2">
                    <Button type="submit">
                        {{ editingClientId ? 'Update Client' : 'Save Client' }}
                    </Button>

                    <Button
                        v-if="editingClientId"
                        type="button"
                        variant="outline"
                        @click="cancelEdit"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Company</TableHead>
                        <TableHead>Address</TableHead>
                        <TableHead>City</TableHead>
                        <TableHead>Country</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="client in filteredClients"
                        :key="client.id"
                    >
                        <TableCell>{{ client.name }}</TableCell>
                        <TableCell>{{ client.email }}</TableCell>
                        <TableCell>{{ client.phone || '-' }}</TableCell>
                        <TableCell>{{ client.company || '-' }}</TableCell>
                        <TableCell>{{ client.address || '-' }}</TableCell>
                        <TableCell>{{ client.city || '-' }}</TableCell>
                        <TableCell>{{ client.country || '-' }}</TableCell>
                        <TableCell>{{ client.status }}</TableCell>
                        <TableCell class="space-x-2">
                            <Link :href="`/clients/${client.id}`">
                                <Button variant="outline"> View </Button>
                            </Link>
                            <Button
                                variant="outline"
                                @click="editClient(client)"
                            >
                                Edit
                            </Button>
                            <Button
                                variant="destructive"
                                @click="deleteClient(client.id)"
                                >Delete</Button
                            >
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
