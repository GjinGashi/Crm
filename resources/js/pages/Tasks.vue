<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';

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
interface Project {
    id: number;
    name: string;
}
interface User {
    id: number;
    name: string;
}
const tasks = ref<Task[]>([]);
const search = ref('');
const projectFilter = ref('All');
const userFilter = ref('All');
const statusFilter = ref('All');
const priorityFilter = ref('All');
const dueDateFilter = ref('');
const projects = ref<Project[]>([]);
const users = ref<User[]>([]);
const isLoading = ref(true);
const actionLoading = ref<number | null>(null);
const error = ref('');
const filteredTasks = computed(() => {
    return tasks.value.filter((task) => {
        const matchesSearch = task.title
            .toLowerCase()
            .includes(search.value.toLowerCase());

        const matchesProject =
            projectFilter.value === 'All' ||
            task.project_id === Number(projectFilter.value);

        const matchesUser =
            userFilter.value === 'All' ||
            task.user_id === Number(userFilter.value);

        const matchesStatus =
            statusFilter.value === 'All' || task.status === statusFilter.value;

        const matchesPriority =
            priorityFilter.value === 'All' ||
            task.priority === priorityFilter.value;

        const matchesDueDate =
            dueDateFilter.value === '' || task.due_date === dueDateFilter.value;

        return (
            matchesSearch &&
            matchesProject &&
            matchesUser &&
            matchesStatus &&
            matchesPriority &&
            matchesDueDate
        );
    });
});
const fetchTasks = async () => {
    isLoading.value = true;
    error.value = '';

    try {
        const response = await fetch('/api/tasks');

        if (!response.ok) {
            throw new Error('Unable to load tasks.');
        }

        tasks.value = await response.json();
    } catch {
        error.value = 'Unable to load tasks. Please try again.';
    } finally {
        isLoading.value = false;
    }
};

const fetchProjects = async () => {
    const response = await fetch('/api/projects');
    projects.value = await response.json();
};

const fetchUsers = async () => {
    const response = await fetch('/api/users');
    users.value = await response.json();
};
onMounted(() => {
    fetchTasks();
    fetchProjects();
    fetchUsers();
});
const deleteTask = async (id: number) => {
    actionLoading.value = id;

    try {
        const response = await fetch(`/api/tasks/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            const data = await response.json();
            toast.error(data.message);
            return;
        }

        tasks.value = tasks.value.filter((task) => task.id !== id);
        toast.success('Task deleted successfully');
    } finally {
        actionLoading.value = null;
    }
};
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Tasks</h1>

                    <p class="text-muted-foreground">
                        Manage tasks, projects, and deadlines.
                    </p>
                </div>

                <Link href="/tasks/create">
                    <Button> Create Task </Button>
                </Link>
            </div>
            <div
                class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-2 lg:grid-cols-6"
            >
                <Input v-model="search" placeholder="Search tasks by name..." />

                <select
                    v-model="projectFilter"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                >
                    <option value="All">Filter by Project</option>
                    <option
                        v-for="project in projects"
                        :key="project.id"
                        :value="project.id"
                    >
                        {{ project.name }}
                    </option>
                </select>

                <select
                    v-model="userFilter"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                >
                    <option value="All">Filter by User</option>
                    <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.id"
                    >
                        {{ user.name }}
                    </option>
                </select>

                <select
                    v-model="statusFilter"
                    class="border-input bg-background w-full rounded-md border px-3 py-2 text-sm"
                >
                    <option value="All">Filter by Status</option>
                    <option value="Todo">Todo</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
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

                <div class="bg-background rounded-md border px-3 py-2">
                    <label
                        class="text-muted-foreground mb-1 block text-xs font-medium"
                    >
                        Due Date
                    </label>
                    <Input
                        v-model="dueDateFilter"
                        type="date"
                        class="border-0 p-0 shadow-none focus-visible:ring-0"
                    />
                </div>
            </div>
            <div
                class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm"
            >
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
                        <TableRow v-if="isLoading">
                            <TableCell
                                :colspan="7"
                                class="text-muted-foreground h-24 text-center"
                            >
                                Loading tasks...
                            </TableCell>
                        </TableRow>

                        <TableRow v-else-if="error">
                            <TableCell :colspan="7" class="h-32 text-center">
                                <div
                                    class="flex flex-col items-center justify-center gap-2"
                                >
                                    <p class="text-destructive font-medium">
                                        {{ error }}
                                    </p>

                                    <Button
                                        variant="outline"
                                        size="sm"
                                        @click="fetchTasks"
                                    >
                                        Try again
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>

                        <TableRow v-else-if="filteredTasks.length === 0">
                            <TableCell :colspan="7" class="h-32 text-center">
                                <div
                                    class="flex flex-col items-center justify-center gap-1"
                                >
                                    <p class="font-medium text-slate-900">
                                        {{
                                            search ||
                                            projectFilter !== 'All' ||
                                            userFilter !== 'All' ||
                                            statusFilter !== 'All' ||
                                            priorityFilter !== 'All' ||
                                            dueDateFilter
                                                ? 'No tasks match your filters.'
                                                : 'No tasks yet.'
                                        }}
                                    </p>

                                    <p class="text-muted-foreground text-sm">
                                        {{
                                            search ||
                                            projectFilter !== 'All' ||
                                            userFilter !== 'All' ||
                                            statusFilter !== 'All' ||
                                            priorityFilter !== 'All' ||
                                            dueDateFilter
                                                ? 'Try adjusting your search or filters.'
                                                : 'Create your first task to get started.'
                                        }}
                                    </p>
                                </div>
                            </TableCell>
                        </TableRow>

                        <TableRow
                            v-else
                            v-for="task in filteredTasks"
                            :key="task.id"
                        >
                            <TableCell>{{ task.title }}</TableCell>

                            <TableCell>
                                {{
                                    projects.find(
                                        (project) =>
                                            project.id === task.project_id,
                                    )?.name || '-'
                                }}
                            </TableCell>

                            <TableCell>
                                {{
                                    users.find(
                                        (user) => user.id === task.user_id,
                                    )?.name || '-'
                                }}
                            </TableCell>

                            <TableCell>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-slate-100 text-slate-700':
                                            task.status === 'Todo',
                                        'bg-blue-50 text-blue-700':
                                            task.status === 'In Progress',
                                        'bg-emerald-50 text-emerald-700':
                                            task.status === 'Completed',
                                        'bg-red-50 text-red-700':
                                            task.status === 'Canceled',
                                    }"
                                >
                                    {{ task.status }}
                                </span>
                            </TableCell>

                            <TableCell>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-slate-100 text-slate-700':
                                            task.priority === 'Low',
                                        'bg-blue-50 text-blue-700':
                                            task.priority === 'Medium',
                                        'bg-amber-50 text-amber-700':
                                            task.priority === 'High',
                                        'bg-red-50 text-red-700':
                                            task.priority === 'Urgent',
                                    }"
                                >
                                    {{ task.priority }}
                                </span>
                            </TableCell>

                            <TableCell>
                                {{ task.due_date || '-' }}
                            </TableCell>
                            <TableCell>
                                <div class="flex items-center gap-2">
                                    <Link :href="`/tasks/${task.id}`">
                                        <Button variant="outline" size="sm">
                                            View
                                        </Button>
                                    </Link>

                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="outline" size="sm">
                                                Actions
                                            </Button>
                                        </DropdownMenuTrigger>

                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem as-child>
                                                <Link
                                                    :href="`/tasks/${task.id}/edit`"
                                                >
                                                    Edit
                                                </Link>
                                            </DropdownMenuItem>

                                            <AlertDialog>
                                                <AlertDialogTrigger as-child>
                                                    <DropdownMenuItem
                                                        class="text-destructive focus:text-destructive"
                                                        @select.prevent
                                                    >
                                                        Delete
                                                    </DropdownMenuItem>
                                                </AlertDialogTrigger>

                                                <AlertDialogContent>
                                                    <AlertDialogHeader>
                                                        <AlertDialogTitle>
                                                            Delete task?
                                                        </AlertDialogTitle>

                                                        <AlertDialogDescription>
                                                            This action cannot
                                                            be undone. This will
                                                            permanently delete
                                                            the task and its
                                                            record.
                                                        </AlertDialogDescription>
                                                    </AlertDialogHeader>

                                                    <AlertDialogFooter>
                                                        <AlertDialogCancel>
                                                            Cancel
                                                        </AlertDialogCancel>

                                                        <AlertDialogAction
                                                            :disabled="
                                                                actionLoading ===
                                                                task.id
                                                            "
                                                            @click="
                                                                deleteTask(
                                                                    task.id,
                                                                )
                                                            "
                                                        >
                                                            {{
                                                                actionLoading ===
                                                                task.id
                                                                    ? 'Deleting...'
                                                                    : 'Delete'
                                                            }}
                                                        </AlertDialogAction>
                                                    </AlertDialogFooter>
                                                </AlertDialogContent>
                                            </AlertDialog>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
