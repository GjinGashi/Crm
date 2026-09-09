<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('allows a user to log in through the API', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'token',
            'user',
        ])
        ->assertJsonPath('user.id', $user->id);
});

it('rejects invalid API login credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'The provided details are incorrect',
        ]);
});

it('requires authentication to access the API user endpoint', function () {
    $response = $this->getJson('/api/user');

    $response->assertUnauthorized();
});

it('allows an authenticated API user to access the user endpoint', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this
        ->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/user');

    $response
        ->assertOk()
        ->assertJsonPath('id', $user->id);
});
