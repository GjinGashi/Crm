<?php

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('searches clients, projects, and tasks', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create([
        'name' => 'Acme Corporation',
        'archived_at' => null,
    ]);

    $project = Project::factory()->create([
        'client_id' => $client->id,
        'name' => 'Acme Website Project',
    ]);

    $task = Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
        'title' => 'Acme Website Task',
    ]);

    $response = $this
        ->actingAs($user)
        ->getJson('/api/search?q=Acme');

    $response
        ->assertOk()
        ->assertJsonPath('clients.0.id', $client->id)
        ->assertJsonPath('projects.0.id', $project->id)
        ->assertJsonPath('tasks.0.id', $task->id);
});

it('returns empty search results when no query is provided', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->getJson('/api/search?q=');

    $response
        ->assertOk()
        ->assertJson([
            'clients' => [],
            'projects' => [],
            'tasks' => [],
        ]);
});

it('requires authentication for global search', function () {
    $response = $this->getJson('/api/search?q=Acme');

    $response->assertUnauthorized();
});
