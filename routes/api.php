<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('clients', ClientController::class)
    ->middleware('auth:sanctum');
Route::patch('/clients/{client}/archive', [ClientController::class, 'archive'])
    ->middleware('auth:sanctum');
Route::patch('/clients/{client}/restore', [ClientController::class, 'restore'])
    ->middleware('auth:sanctum');
Route::apiResource('projects', ProjectController::class)
    ->middleware('auth:sanctum');
Route::patch('/projects/{project}/archive', [ProjectController::class, 'archive'])
    ->middleware('auth:sanctum');
Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])
    ->middleware('auth:sanctum');
Route::apiResource('tasks', TaskController::class)
    ->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/users', function () {
    return User::select('id', 'name')->get();
})->middleware('auth:sanctum');
