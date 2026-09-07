  <script setup lang="ts">
  import { Head, router } from '@inertiajs/vue3';
  import { Button } from '@/components/ui/button';
  import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
  } from '@/components/ui/card'
  import AppLayout from '@/layouts/AppLayout.vue';
  interface Props {
    clientsCount: number
    projectsCount: number
    tasksCount: number
    pendingProjects: number
    inProgressProjects: number
    completedProjects: number
    cancelledProjects: number

    pendingTasks: number
    inProgressTasks: number
    completedTasks: number
    cancelledTasks: number
  }
  defineProps<Props>()
</script>

  <template>
    <AppLayout>

      <Head title="Dashboard" />
      <div class="min-h-screen bg-background p-6">
        <div class="mx-auto max-w-7xl space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold tracking-tight">
                Dashboard
              </h1>
              <p class="text-muted-foreground">Welcome to your CRM.</p>
            </div>
            <Button variant="outline" type="button" @click="router.post('/logout')">
              Logout
            </Button>
          </div>
          <div class="grid gap-4 md:grid-cols-3">
            <Card>
              <CardHeader>
                <CardTitle>Clients</CardTitle>
              </CardHeader>
              <CardContent>
                <p class="text-3xl font-bold">
                  {{ clientsCount }}
                </p>
              </CardContent>
            </Card>
            <Card>
              <CardHeader>
                <CardTitle>Projects</CardTitle>
              </CardHeader>
              <CardContent>
                <p class="text-3xl font-bold">
                  {{ projectsCount }}
                </p>
              </CardContent>
            </Card>
            <Card>
              <CardHeader>
                <CardTitle>Tasks</CardTitle>
              </CardHeader>
              <CardContent>
                <p class="text-3xl font-bold">
                  {{ tasksCount }}
                </p>
              </CardContent>
            </Card>
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle>Project Status</CardTitle>
              </CardHeader>
              <CardContent class="space-y-2">
                <p>Pending: {{ pendingProjects }}</p>
                <p>In Progress: {{ inProgressProjects }}</p>
                <p>Completed: {{ completedProjects }}</p>
                <p>Cancelled: {{ cancelledProjects }}</p>
              </CardContent>
            </Card>
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle>Task Status</CardTitle>
              </CardHeader>

              <CardContent class="space-y-2">
                <p>Pending: {{ pendingTasks }}</p>
                <p>In Progress: {{ inProgressTasks }}</p>
                <p>Completed: {{ completedTasks }}</p>
                <p>Cancelled: {{ cancelledTasks }}</p>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
