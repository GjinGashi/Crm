<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an admin to access user management', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this
        ->actingAs($admin)
        ->get('/users');

    $response->assertSuccessful();
});

it('prevents a regular user from accessing user management', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/users');

    $response->assertForbidden();
});

it('prevents a regular user from managing users', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $anotherUser = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch("/users/{$anotherUser->id}", [
            'name' => 'Updated Name',
            'email' => $anotherUser->email,
            'role' => 'user',
        ]);

    $response->assertForbidden();
});

it('allows an admin to update a user', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this
        ->actingAs($admin)
        ->patch("/users/{$user->id}", [
            'name' => 'Updated User',
            'email' => $user->email,
            'role' => 'admin',
        ]);

    $response->assertRedirect(route('users'));

    expect($user->refresh()->role)->toBe('admin');
});
