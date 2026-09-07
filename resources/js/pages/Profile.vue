<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
const page = usePage()
const errors = page.props.errors
const submitted = ref(false)
const editing = ref(false)
const name = ref(page.props.auth.user.name)
const email = ref(page.props.auth.user.email)
function startEditing() {
    name.value = page.props.auth.user.name
    email.value = page.props.auth.user.email
    currentPassword.value = ''
    password.value = ''
    passwordConfirmation.value = ''
    submitted.value = false
    editing.value = true
}
const currentPassword = ref('')
const password = ref('')
const passwordConfirmation = ref('')
function saveProfile() {
    submitted.value = true
    router.patch('/profile', {
        name: name.value,
        email: email.value,
        current_password: currentPassword.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
    }, {
        preserveState: true,
        onSuccess: () => {
            editing.value = false
            submitted.value = false
            currentPassword.value = ''
            password.value = ''
            passwordConfirmation.value = ''
        },
        onError: () => {
            editing.value = true
        },
    })
}



</script>

<template>
<AppLayout>
    <Head title="Profile" />
    <div class="p-6">
        <Card class="max-w-2xl">
            <CardHeader>
                <CardTitle>Profile</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <p class="text-sm text-muted-foreground">Name</p>
                    <Input v-if="editing" v-model="name" type="text" />
                    <p v-if="editing && submitted && $page.props.errors.name" class="text-sm text-destructive">{{
                        $page.props.errors.name }}</p>
                    <p v-else class="font-medium">{{ $page.props.auth.user.name }}</p>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground">Email</p>
                    <Input v-if="editing" v-model="email" type="email" />
                    <p v-if="editing && submitted && $page.props.errors.email" class="text-sm text-destructive">
                        {{ $page.props.errors.email }}</p>
                    <p v-else class="font-medium">{{ $page.props.auth.user.email }}</p>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground">Role</p>
                    <p class="font-medium">{{ $page.props.auth.user.role }}</p>
                </div>
                <template v-if="editing">
                    <Input v-model="currentPassword" type="password" placeholder="Current Password" />
                    <p v-if="editing && submitted && $page.props.errors.current_password"
                        class="text-sm text-destructive">{{ $page.props.errors.current_password }}</p>
                    <Input v-model="password" type="password" placeholder="New Password" />
                    <p v-if="editing && submitted && $page.props.errors.password" class="text-sm text-destructive">{{
                        $page.props.errors.password }}</p>
                    <Input v-model="passwordConfirmation" type="password" placeholder="Confirm New Password" />
                </template>
                <div class="flex gap-2">
                    <Button v-if="!editing" type="button" @click="startEditing">Edit</Button>
                    <template v-else>
                        <Button type="button" @click="saveProfile">Save</Button>
                        <Button variant="outline" type="button" @click="editing = false">Cancel</Button>
                    </template>
                </div>
            </CardContent>
        </Card>
    </div>
    </AppLayout>
</template>