<?php

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an authenticated user to create a client', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/clients', [
            'name' => 'Acme Corporation',
            'email' => 'contact@acme.test',
            'phone' => '123456789',
            'company' => 'Acme Corporation',
            'address' => '123 Main Street',
            'city' => 'Pristina',
            'country' => 'Kosovo',
            'status' => 'Active',
            'notes' => 'Important client',
        ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('name', 'Acme Corporation')
        ->assertJsonPath('email', 'contact@acme.test');

    $this->assertDatabaseHas('clients', [
        'name' => 'Acme Corporation',
        'email' => 'contact@acme.test',
        'status' => 'Active',
    ]);
});

it('validates client data when creating a client', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/clients', [
            'name' => '',
            'email' => 'not-an-email',
            'status' => 'Invalid',
        ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'status',
        ]);
});

it('allows an authenticated user to update a client', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create([
        'name' => 'Old Client Name',
        'email' => 'old@example.test',
        'status' => 'Active',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->putJson("/api/clients/{$client->id}", [
            'name' => 'Updated Client Name',
            'email' => 'updated@example.test',
            'phone' => '987654321',
            'company' => 'Updated Company',
            'address' => '456 Updated Street',
            'city' => 'Pristina',
            'country' => 'Kosovo',
            'status' => 'Lead',
            'notes' => 'Updated notes',
        ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('name', 'Updated Client Name')
        ->assertJsonPath('status', 'Lead');

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => 'Updated Client Name',
        'email' => 'updated@example.test',
        'status' => 'Lead',
    ]);
});

it('allows an authenticated user to archive and restore a client', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create([
        'status' => 'Active',
        'archived_at' => null,
    ]);

    $archiveResponse = $this
        ->actingAs($user, 'sanctum')
        ->patchJson("/api/clients/{$client->id}/archive");

    $archiveResponse
        ->assertOk()
        ->assertJson([
            'message' => 'Client archived successfully',
        ]);

    $client->refresh();

    expect($client->status)->toBe('Archived');
    expect($client->archived_at)->not->toBeNull();

    $restoreResponse = $this
        ->actingAs($user, 'sanctum')
        ->patchJson("/api/clients/{$client->id}/restore");

    $restoreResponse
        ->assertOk()
        ->assertJson([
            'message' => 'Client restored successfully',
        ]);

    $client->refresh();

    expect($client->status)->toBe('Active');
    expect($client->archived_at)->toBeNull();
});

it('allows an authenticated user to delete a client without related projects', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create();

    $response = $this
        ->actingAs($user, 'sanctum')
        ->deleteJson("/api/clients/{$client->id}");

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'Client deleted successfully',
        ]);

    $this->assertDatabaseMissing('clients', [
        'id' => $client->id,
    ]);
});

it('returns the client with its related projects', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create();

    $project = Project::factory()->create([
        'client_id' => $client->id,
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson("/api/clients/{$client->id}");

    $response
        ->assertOk()
        ->assertJsonPath('id', $client->id)
        ->assertJsonPath('projects.0.id', $project->id);
});

it('can filter archived clients through the API', function () {
    $user = User::factory()->create();

    $activeClient = Client::factory()->create([
        'archived_at' => null,
        'status' => 'Active',
    ]);

    $archivedClient = Client::factory()->create([
        'archived_at' => now(),
        'status' => 'Archived',
    ]);

    $response = $this
        ->actingAs($user, 'sanctum')
        ->getJson('/api/clients?archived=1');

    $response->assertOk();

    $ids = collect($response->json())->pluck('id');

    expect($ids)->toContain($archivedClient->id);
    expect($ids)->not->toContain($activeClient->id);
});
