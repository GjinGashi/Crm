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
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge';

interface Task {
    id: number
    project_id: number
    user_id: number
    title: string
    description: string | null
    status: string
    priority: string
    start_time: string | null
    end_time: string | null
    due_date: string | null
}
interface Project {
    id: number
    name: string
}
interface User {
    id: number
    name: string
}
const tasks = ref<Task[]>([])
const search = ref('')
const projectFilter = ref('All')
const userFilter = ref('All')
const statusFilter = ref('All')
const priorityFilter = ref('All')
const dueDateFilter = ref('')
const filteredTasks = computed(() => {
    return tasks.value.filter(task => {
        const matchesSearch = task.title
            .toLowerCase()
            .includes(search.value.toLowerCase())

        const matchesProject =
            projectFilter.value === 'All' ||
            task.project_id === Number(projectFilter.value)

        const matchesUser =
            userFilter.value === 'All' ||
            task.user_id === Number(userFilter.value)

        const matchesStatus =
            statusFilter.value === 'All' ||
            task.status === statusFilter.value

        const matchesPriority =
            priorityFilter.value === 'All' ||
            task.priority === priorityFilter.value

        const matchesDueDate =
            dueDateFilter.value === '' ||
            task.due_date === dueDateFilter.value

        return matchesSearch &&
            matchesProject &&
            matchesUser &&
            matchesStatus &&
            matchesPriority &&
            matchesDueDate
    })
})
const projects = ref<Project[]>([])
const users = ref<User[]>([])
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
const fetchUsers = async () => {
    const response = await fetch('/api/users')
    users.value = await response.json()
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
        user_id: task.user_id,
        title: task.title,
        description: task.description ?? '',
        status: task.status,
        priority: task.priority,
        start_time: task.start_time ?? '',
        end_time: task.end_time ?? '',
        due_date: task.due_date ?? '',
    }
}
function cancelEdit() {
    editingTaskId.value = null
    form.value = {
        project_id: null,
        user_id: null,
        title: '',
        description: '',
        status: 'Todo',
        priority: 'Low',
        start_time: '',
        end_time: '',
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
    fetchUsers()
})

</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-3xl font-bold tracking-tight">Tasks</h1>
            <p class="text-muted-foreground">
                Manage tasks,projects,and deadlines.
            </p>
            <Input v-model="search" placeholder="Search tasks by name" />
            <select v-model="projectFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                <option value="All">Filter by Project</option>
                <option v-for="project in projects" :key="project.id" :value="project.id">
                    {{ project.name }}
                </option>
            </select>
            <select v-model="userFilter" class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                <option value="All">Filter by User</option>

                <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                </option>
            </select>
            <select v-model="statusFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                <option value="All">Filter by Status</option>
                <option value="Todo">Todo</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <select v-model="priorityFilter"
                class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
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
            <form @submit.prevent="editingTaskId ? updateTask() : createTask()" class="space-y-4">
                <select v-model="form.project_id"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option :value="null">Select A Project</option>
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                        {{ project.name }}
                    </option>
                </select>
                <select v-model="form.user_id"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option :value="null">Assign To User</option>

                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <Input v-model="form.title" type="text" placeholder="Task Title" />
                <Textarea v-model="form.description" placeholder="Description" />
                <select v-model="form.status"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option value="Todo">Todo</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <select v-model="form.priority"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
                <div>
                    <label class="text-sm font-medium">Start Time</label>
                    <Input v-model="form.start_time" type="datetime-local" />
                </div>

                <div>
                    <label class="text-sm font-medium">End Time</label>
                    <Input v-model="form.end_time" type="datetime-local" />
                </div>

                <div>
                    <label class="text-sm font-medium">Due Date</label>
                    <Input v-model="form.due_date" type="date" />
                </div>
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
                        <TableHead>Assigned To</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Priority</TableHead>
                        <TableHead>Due Date</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="task in filteredTasks" :key="task.id">
                        <TableCell>{{ task.title }}</TableCell>


                        <TableCell>
                            {{projects.find(project => project.id === task.project_id)?.name || '-'}}
                        </TableCell>

                        <TableCell>
                            {{users.find(user => user.id === task.user_id)?.name || '-'}}
                        </TableCell>

                        <TableCell>
                            <Badge variant="outline">
                                {{ task.status }}
                            </Badge>
                        </TableCell>

                        <TableCell>
                            <Badge variant="outline">
                                {{ task.priority }}
                            </Badge>
                        </TableCell>

                        <TableCell>
                            {{ task.due_date || '-' }}
                        </TableCell>
                        <TableCell class="space-x-2">
                            <Link :href="`/tasks/${task.id}`">
                                <Button variant="outline">
                                    View
                                </Button>
                            </Link>
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