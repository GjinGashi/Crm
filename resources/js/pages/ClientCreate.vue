<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';

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
const errors = ref<Record<string, string[]>>({});
async function createClient() {
    const response = await fetch('/api/clients', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(form.value),
    });

    if (!response.ok) {
        const data = await response.json();

        if (data.errors) {
            errors.value = data.errors;
        }

        return;
    }

    window.location.href = '/clients';
}
</script>

<template>
    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-6 p-6 lg:p-8">
            <div>
                <h1
                    class="text-2xl font-semibold tracking-tight text-slate-900"
                >
                    Create Client
                </h1>
                <p class="text-muted-foreground mt-1 text-sm">
                    Add a new client
                </p>
            </div>

            <form
                class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                @submit.prevent="createClient"
            >
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">
                            Name <span class="text-destructive">*</span>
                        </label>
                        <Input v-model="form.name" placeholder="Client name" />
                        <p v-if="errors.name" class="text-destructive text-sm">
                            {{ errors.name[0] }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">
                            Email <span class="text-destructive">*</span>
                        </label>
                        <Input
                            v-model="form.email"
                            type="email"
                            placeholder="client@example.com"
                        />
                        <p v-if="errors.email" class="text-destructive text-sm">
                            {{ errors.email[0] }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Phone</label>
                        <Input
                            v-model="form.phone"
                            placeholder="Phone number"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Company</label>
                        <Input
                            v-model="form.company"
                            placeholder="Company name"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Address</label>
                        <Input v-model="form.address" placeholder="Address" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">City</label>
                        <Input v-model="form.city" placeholder="City" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Country</label>
                        <Input v-model="form.country" placeholder="Country" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Status</label>
                        <select
                            v-model="form.status"
                            class="border-input bg-background ring-offset-background focus-visible:ring-ring flex h-10 w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                        >
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Lead">Lead</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Notes</label>
                    <Textarea
                        v-model="form.notes"
                        placeholder="Additional notes about this client"
                        rows="5"
                    />
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/clients">
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </Link>

                    <Button type="submit"> Save Client </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
