<script setup lang="ts">
import { ref, onMounted } from 'vue';
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
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge';

interface Client {
    id: number
    name: string
}

interface Project {
    id: number
    client_id: number
    name: string
    description: string | null
    status: string
}
const projects = ref<Project[]>([])
const clients = ref<Client[]>([])
const editingProjectId = ref<number | null>(null)
const form = ref({
    client_id: null as number | null,
    name: '',
    description: '',
    status: 'pending',
})
onMounted(async () => {
    const projectsResponse = await fetch('/api/projects')
    projects.value = await projectsResponse.json()
    const clientsResponse = await fetch('/api/clients')
    clients.value = await clientsResponse.json()
})
async function createProject() {
    const response = await fetch('/api/projects', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    })
    const project = await response.json()
    projects.value.push(project)
    form.value = {
        client_id: null,
        name: '',
        description: '',
        status: 'pending',
    }
}
function editProject(project: Project) {
    editingProjectId.value = project.id

    form.value = {
        client_id: project.client_id,
        name: project.name,
        description: project.description ?? '',
        status: project.status,
    }
}
function cancelEdit() {
    editingProjectId.value = null
    form.value = {
        client_id: null,
        name: '',
        description: '',
        status: 'pending',
    }
}
async function updateProject() {
    if (editingProjectId.value === null) return

    const response = await fetch(`/api/projects/${editingProjectId.value}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(form.value),
    })
    const updatedProject = await response.json()
    const index = projects.value.findIndex(
        project => project.id === editingProjectId.value
    )
    if (index !== -1) {
        projects.value[index] = updatedProject
    }
    editingProjectId.value = null
    form.value = {
        client_id: null,
        name: '',
        description: '',
        status: 'pending',
    }
}
async function deleteProject(project: Project) {
    await fetch(`/api/projects/${project.id}`, {
        method: 'DELETE'
    })
    projects.value = projects.value.filter(
        p => p.id !== project.id
    )
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-3xl font-bold tracking-tight">Projects</h1>
            <p class="text-muted-foreground">Manage Projects and their clients</p>
            <form @submit.prevent="editingProjectId ? updateProject() : createProject()" class="space-y-4">
                <Input v-model="form.name" placeholder="Project name" />
                <select v-model="form.client_id"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option :value="null">Select Client</option>
                    <option v-for="client in clients" :key="client.id" :value="client.id">
                        {{ client.name }}
                    </option>
                </select>
                <Textarea v-model="form.description" placeholder="Description" />
                <select v-model="form.status"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <div class="flex gap-2">
                    <Button type="submit">
                        {{ editingProjectId ? 'Update Project' : 'Save Project' }}
                    </Button>

                    <Button v-if="editingProjectId" type="button" variant="outline" @click="cancelEdit">
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
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="project in projects" :key="project.id">
                        <TableCell>{{ project.name }}</TableCell>
                        <TableCell>{{clients.find(client => client.id === project.client_id)?.name || '-'}}</TableCell>
                        <TableCell>
                            <Badge>
                                {{ project.status }}
                            </Badge>
                        </TableCell>
                        <TableCell class="space-x-2">
                            <Button variant="outline" @click="editProject(project)">
                                Edit
                            </Button>
                            <Button variant="destructive" @click="deleteProject(project)">
                                Delete
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>