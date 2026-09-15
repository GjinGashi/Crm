<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('auth:sanctum');
Route::get('/dashboard', function (Request $request) {
    return response()->json([
        'user' => $request->user(),

        'clientsCount' => Client::count(),
        'projectsCount' => Project::count(),
        'tasksCount' => Task::count(),

        'planningProjects' => Project::where('status', 'planning')->count(),
        'inProgressProjects' => Project::where('status', 'in progress')->count(),
        'completedProjects' => Project::where('status', 'completed')->count(),
        'CanceledProjects' => Project::where('status', 'Canceled')->count(),

        'todoTasks' => Task::where('status', 'Todo')->count(),
        'inProgressTasks' => Task::where('status', 'in progress')->count(),
        'completedTasks' => Task::where('status', 'completed')->count(),
        'CanceledTasks' => Task::where('status', 'Canceled')->count(),
    ]);
})->middleware('auth:sanctum');
Route::patch('/profile', [AuthController::class, 'updateProfile'])
    ->middleware('auth:sanctum');
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
Route::get('/search', function (Request $request) {
    $query = $request->string('q')->trim();

    if ($query->isEmpty()) {
        return response()->json([
            'clients' => [],
            'projects' => [],
            'tasks' => [],
        ]);
    }

    return response()->json([
        'clients' => Client::where(function ($clientQuery) use ($query) {
            $clientQuery
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%");
        })
            ->whereNull('archived_at')
            ->limit(5)
            ->get([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
            ]),

        'projects' => Project::where('name', 'like', "%{$query}%")
            ->whereNull('archived_at')
            ->limit(5)
            ->get(['id', 'name']),

        'tasks' => Task::where('title', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title']),
    ]);
})->middleware('auth:sanctum');