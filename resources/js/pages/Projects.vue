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
import { Badge } from '@/components/ui/badge';

interface Client {
    id: number;
    name: string;
}

interface Project {
    id: number;
    client_id: number;
    name: string;
    description: string | null;
    status: string;
    priority: string;
    start_date: string | null;
    due_date: string | null;
    budget: number | null;
}
const projects = ref<Project[]>([]);
const dueDateFilter = ref('');
const priorityFilter = ref('All');
const clientFilter = ref('All');
const statusFilter = ref('All');
const search = ref('');

const filteredProjects = computed(() => {
    return projects.value.filter((project) => {
        const matchesSearch = project.name
            .toLowerCase()
            .includes(search.value.toLowerCase());

        const matchesClient =
            clientFilter.value === 'All' ||
            project.client_id === Number(clientFilter.value);

        const matchesStatus =
            statusFilter.value === 'All' ||
            project.status === statusFilter.value;

        const matchesPriority =
            priorityFilter.value === 'All' ||
            project.priority === priorityFilter.value;
        const matchesDueDate =
            dueDateFilter.value === '' ||
            project.due_date === dueDateFilter.value;

        return (
            matchesSearch &&
            matchesClient &&
            matchesStatus &&
            matchesPriority &&
            matchesDueDate
        );
    });
});
const clients = ref<Client[]>([]);
const editingProjectId = ref<number | null>(null);
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
onMounted(async () => {
    const projectsResponse = await fetch('/api/projects');
    projects.value = await projectsResponse.json();
    const clientsResponse = await fetch('/api/clients');
    clients.value = await clientsResponse.json();
});
async function createProject() {
    const response = await fetch('/api/projects', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    });
    const project = await response.json();
    projects.value.push(project);
    form.value = {
        client_id: null as number | null,
        name: '',
        description: '',
        status: 'Planning',
        priority: 'Low',
        start_date: '',
        due_date: '',
        budget: '',
    };
}
function editProject(project: Project) {
    editingProjectId.value = project.id;

    form.value = {
        client_id: project.client_id,
        name: project.name,
        description: project.description ?? '',
        status: project.status,
        priority: project.priority,
        start_date: project.start_date ?? '',
        due_date: project.due_date ?? '',
        budget: project.budget ?? '',
    };
}
function cancelEdit() {
    editingProjectId.value = null;
    form.value = {
        client_id: null as number | null,
        name: '',
        description: '',
        status: 'Planning',
        priority: 'Low',
        start_date: '',
        due_date: '',
        budget: '',
    };
}
async function updateProject() {
    if (editingProjectId.value === null) return;

    const response = await fetch(`/api/projects/${editingProjectId.value}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    });
    const updatedProject = await response.json();
    const index = projects.value.findIndex(
        (project) => project.id === editingProjectId.value,
    );
    if (index !== -1) {
        projects.value[index] = updatedProject;
    }
    editingProjectId.value = null;
    form.value = {
        client_id: null as number | null,
        name: '',
        description: '',
        status: 'Planning',
        priority: 'Low',
        start_date: '',
        due_date: '',
        budget: '',
    };
}
async function deleteProject(project: Project) {
    await fetch(`/api/projects/${project.id}`, {
        method: 'DELETE',
    });
    projects.value = projects.value.filter((p) => p.id !== project.id);
}
</script>

```vue
<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <h1 class="text-3xl font-bold tracking-tight">Projects</h1>
            <p class="text-muted-foreground">
                Manage Projects and their clients
            </p>
            <Input v-model="search" placeholder="Search projects by name" />
            <select
                v-model="clientFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
            >
                <option value="All">Filter by Client</option>

                <option
                    v-for="client in clients"
                    :key="client.id"
                    :value="client.id"
                >
                    {{ client.name }}
                </option>
            </select>
            <select
                v-model="statusFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
            >
                <option value="All">Filter by Status</option>
                <option value="Planning">Planning</option>
                <option value="In Progress">In Progress</option>
                <option value="On Hold">On Hold</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <select
                v-model="priorityFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
            >
                <option value="All">Filter by Priority</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>
            <div>
                <label class="text-sm font-medium">Filter by Due Date</label>
                <Input v-model="dueDateFilter" type="date" />
            </div>
            <form
                @submit.prevent="
                    editingProjectId ? updateProject() : createProject()
                "
                class="space-y-4"
            >
                <Input v-model="form.name" placeholder="Project name" />

                <select
                    v-model="form.client_id"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
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

                <Textarea
                    v-model="form.description"
                    placeholder="Description"
                />

                <select
                    v-model="form.status"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                >
                    <option value="Planning">Planning</option>
                    <option value="In Progress">In Progress</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>

                <select
                    v-model="form.priority"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                >
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>

                <div>
                    <label class="text-sm font-medium">Start Date</label>
                    <Input v-model="form.start_date" type="date" />
                </div>

                <div>
                    <label class="text-sm font-medium">Due Date</label>
                    <Input v-model="form.due_date" type="date" />
                </div>

                <Input
                    v-model="form.budget"
                    type="number"
                    min="0"
                    step="0.01"
                    placeholder="Budget"
                />

                <div class="flex gap-2">
                    <Button type="submit">
                        {{
                            editingProjectId ? 'Update Project' : 'Save Project'
                        }}
                    </Button>

                    <Button
                        v-if="editingProjectId"
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
                        <TableHead>Project</TableHead>
                        <TableHead>Client</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Priority</TableHead>
                        <TableHead>Start Date</TableHead>
                        <TableHead>Due Date</TableHead>
                        <TableHead>Budget</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="project in filteredProjects"
                        :key="project.id"
                    >
                        <TableCell>
                            {{ project.name }}
                        </TableCell>

                        <TableCell>
                            {{
                                clients.find(
                                    (client) => client.id === project.client_id,
                                )?.name || '-'
                            }}
                        </TableCell>

                        <TableCell>
                            <Badge>
                                {{ project.status }}
                            </Badge>
                        </TableCell>

                        <TableCell>
                            <Badge variant="outline">
                                {{ project.priority }}
                            </Badge>
                        </TableCell>

                        <TableCell>
                            {{ project.start_date || '-' }}
                        </TableCell>

                        <TableCell>
                            {{ project.due_date || '-' }}
                        </TableCell>

                        <TableCell>
                            {{ project.budget ?? '-' }}
                        </TableCell>

                        <TableCell class="space-x-2">
                            <Link :href="`/projects/${project.id}`">
                                <Button variant="outline"> View </Button>
                            </Link>
                            <Button
                                variant="outline"
                                @click="editProject(project)"
                            >
                                Edit
                            </Button>

                            <Button
                                variant="destructive"
                                @click="deleteProject(project)"
                            >
                                Delete
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
```
