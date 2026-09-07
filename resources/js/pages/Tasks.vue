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

interface Task {
    id: number
    project_id: number
    title: string
    description: string | null
    status: string
    due_date: string | null
}
interface Project {
    id: number
    name: string
}
const tasks = ref<Task[]>([])
const projects = ref<Project[]>([])
const form = ref({
    project_id: null as number | null,
    title: '',
    description: '',
    status: 'pending',
    due_date: '',

})
const editingTaskId = ref<number | null>(null)
const fetchTasks = async () => {
    const response = await fetch('/api/tasks')
    tasks.value = await response.json()
}
const fetchProjects = async () => {
    const response = await fetch('/api/projects')
    projects.value = await response.json()
}
const createTask = async () => {
     console.log(form.value)
    const response = await fetch('/api/tasks', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(form.value),
    })

    if (!response.ok) {
        return
    }

    const task = await response.json()
    tasks.value.push(task)
}
const editTask = (task: Task) => {
    editingTaskId.value = task.id

    form.value = {
        project_id: task.project_id,
        title: task.title,
        description: task.description ?? '',
        status: task.status,
        due_date: task.due_date ?? '',
    }
}
function cancelEdit() {
    editingTaskId.value = null
    form.value = {
        project_id: null,
        title: '',
        description: '',
        status: 'pending',
        due_date: '',
    }
}
const updateTask = async () => {
    if (editingTaskId.value === null) return

    const response = await fetch(`/api/tasks/${editingTaskId.value}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    })
    const updatedTask = await response.json()

    const index = tasks.value.findIndex(
        task => task.id === editingTaskId.value
    )
    if (index !== -1) {
        tasks.value[index] = updatedTask
    }
}
const deleteTask = async (id: number) => {
    await fetch(`/api/tasks/${id}`, {
        method: 'DELETE',
    })
    tasks.value = tasks.value.filter(task => task.id !== id)
}
onMounted(() => {
    fetchTasks()
    fetchProjects()
})

</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-3xl font-bold tracking-tight">Tasks</h1>
            <p class="text-muted-foreground">
                Manage tasks,projects,and deadlines.
            </p>
            <form @submit.prevent="editingTaskId ? updateTask() : createTask()" class="space-y-4">
                <select v-model="form.project_id"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option :value="null">Select A Project</option>
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                        {{ project.name }}
                    </option>
                </select>
                <Input v-model="form.title" type="text" placeholder="Task Title" />
                <Textarea v-model="form.description" placeholder="Description" />
                <select v-model="form.status" @change="console.log('STATUS:', form.status)"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <Input v-model="form.due_date" type="date" />
                <div class="flex gap-2">
                    <Button type="submit">
                        {{ editingTaskId ? 'Update Task' : 'Create Task' }}
                    </Button>
                    <Button v-if="editingTaskId" type="button" variant="outline" @click="cancelEdit">
                        Cancel
                    </Button>
                </div>
            </form>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Task</TableHead>
                        <TableHead>Project</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Due Date</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="task in tasks" :key="task.id">
                        <TableCell>{{ task.title }}</TableCell>


                        <TableCell>
                            {{projects.find(project => project.id === task.project_id)?.name || '-'}}
                        </TableCell>
                        <TableCell>
                            <Badge variant="outline">
                                {{ task.status }}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            {{ task.due_date || '-' }}
                        </TableCell>
                        <TableCell class="space-x-2">
                            <Button variant="outline" @click="editTask(task)">
                                Edit
                            </Button>
                            <Button variant="destructive" @click="deleteTask(task.id)">
                                Delete
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>