<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
const page = usePage();
const name = ref('');
const email = ref('');
const password = ref('');
const role = ref('user');

const editingUserId = ref<number | null>(null);
const editName = ref('');
const editEmail = ref('');
const editRole = ref('');

defineProps<{
    users: {
        id: number;
        name: string;
        email: string;
        role: string;
    }[];
}>();
function changeRole(
    user: {
        id: number;
        name: string;
        email: string;
        role: string;
    },
    event: Event,
) {
    const role = (event.target as HTMLSelectElement).value;

    router.patch(`/users/${user.id}`, {
        name: user.name,
        email: user.email,
        role: role,
    });
}
function startEditingUser(user: {
    id: number;
    name: string;
    email: string;
    role: string;
}) {
    editingUserId.value = user.id;
    editName.value = user.name;
    editEmail.value = user.email;
    editRole.value = user.role;
}
function saveEdit() {
    if (editingUserId.value === null) {
        return;
    }
    router.patch(
        `/users/${editingUserId.value}`,
        {
            name: editName.value,
            email: editEmail.value,
            role: editRole.value,
        },
        {
            onSuccess: () => {
                editingUserId.value = null;
                editName.value = '';
                editEmail.value = '';
                editRole.value = '';
            },
        },
    );
}

function cancelEditing() {
    editingUserId.value = null;
    editName.value = '';
    editEmail.value = '';
    editRole.value = '';
}
</script>
<template>
    <AppLayout>
        <Head title="Users" />

        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Users</h1>
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Create User</CardTitle>
                </CardHeader>
                <CardContent>
                    <form
                        novalidate
                        @submit.prevent="
                            router.post('/users', {
                                name: name,
                                email: email,
                                password: password,
                                role: role,
                            })
                        "
                        class="space-y-4"
                    >
                        <Input v-model="name" type="text" placeholder="Name" />
                        <p
                            v-if="page.props.errors.name"
                            class="text-destructive text-sm"
                        >
                            {{ page.props.errors.name }}
                        </p>
                        <Input
                            v-model="email"
                            type="email"
                            placeholder="Email"
                        />
                        <p
                            v-if="page.props.errors.email"
                            class="text-destructive text-sm"
                        >
                            {{ page.props.errors.email }}
                        </p>
                        <Input
                            v-model="password"
                            type="password"
                            placeholder="Password"
                        />
                        <p
                            v-if="page.props.errors.password"
                            class="text-destructive text-sm"
                        >
                            {{ page.props.errors.password }}
                        </p>

                        <Select v-model="role">
                            <SelectTrigger>
                                <SelectValue placeholder="Select Role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="user">User</SelectItem>
                                <SelectItem value="admin">Admin</SelectItem>
                            </SelectContent>
                        </Select>
                        <p
                            v-if="page.props.errors.role"
                            class="text-destructive text-sm"
                        >
                            {{ page.props.errors.role }}
                        </p>
                        <Button type="submit">Create User</Button>
                    </form>
                </CardContent>
            </Card>

            <div>
                <h2 class="mb-4 text-xl font-semibold">All Users</h2>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users" :key="user.id">
                            <TableCell>
                                <div v-if="editingUserId === user.id">
                                    <Input v-model="editName" />
                                </div>
                                <div v-else>{{ user.name }}</div>
                            </TableCell>
                            <TableCell>
                                <div v-if="editingUserId === user.id">
                                    <Input v-model="editEmail" type="email" />
                                </div>
                                <div v-else>
                                    {{ user.email }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div v-if="editingUserId === user.id">
                                    <Select v-model="editRole">
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select Role"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="user"
                                                >User</SelectItem
                                            >
                                            <SelectItem value="admin"
                                                >Admin</SelectItem
                                            >
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div v-else>
                                    <select
                                        :value="user.role"
                                        @change="changeRole(user, $event)"
                                        class="border-input bg-background rounded-md border px-3 py-2 text-sm"
                                    >
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </TableCell>
                            <TableCell class="space-x-2">
                                <div v-if="editingUserId === user.id">
                                    <Button type="button" @click="saveEdit"
                                        >Save</Button
                                    >
                                    <Button
                                        variant="outline"
                                        type="button"
                                        @click="cancelEditing"
                                        >Cancel</Button
                                    >
                                </div>
                                <div v-else>
                                    <Button
                                        variant="outline"
                                        type="button"
                                        @click="startEditingUser(user)"
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        type="button"
                                        @click="
                                            router.delete(`/users/${user.id}`)
                                        "
                                    >
                                        Delete
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
