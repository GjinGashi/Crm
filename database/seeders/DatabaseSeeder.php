<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        User::factory(2)->create();

        $clients = Client::factory(5)->create();

        foreach ($clients as $client) {
            $projects = Project::factory(2)->create([
                'client_id' => $client->id,
            ]);

            foreach ($projects as $project) {
                Task::factory(2)->create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}

