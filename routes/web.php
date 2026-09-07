<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome', [
        'clientsCount' => Client::count(),
        'projectsCount' => Project::count(),
        'tasksCount' => Task::count(),
        'pendingProjects' => Project::where('status', 'pending')->count(),
        'inProgressProjects' => Project::where('status', 'in progress')->count(),
        'completedProjects' => Project::where('status', 'completed')->count(),
        'cancelledProjects' => Project::where('status', 'cancelled')->count(),

        'pendingTasks' => Task::where('status', 'pending')->count(),
        'inProgressTasks' => Task::where('status', 'in progress')->count(),
        'completedTasks' => Task::where('status', 'completed')->count(),
        'cancelledTasks' => Task::where('status', 'cancelled')->count(),
    ]);
})->middleware('auth')->name('home');

Route::inertia('/clients', 'Clients')
    ->middleware('auth')
    ->name('clients');
Route::inertia('/clients/{client}', 'ClientShow')
    ->middleware('auth')
    ->name('clients.details');
Route::inertia('/projects', 'Projects')
    ->middleware('auth')
    ->name('Projects');
Route::inertia('/projects/{project}', 'ProjectShow')
    ->middleware('auth')
    ->name('projects.details');
Route::inertia('/tasks', 'Tasks')
    ->middleware('auth')
    ->name('Tasks');
Route::inertia('/tasks/{task}', 'TaskShow')
    ->middleware('auth')
    ->name('tasks.details');
Route::inertia('/login', 'Auth/Login')->name('login');
Route::inertia('/profile', 'Profile')->name('profile')->middleware('auth');
Route::patch('/profile', [AuthController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('users');
Route::patch('/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('users.update');
Route::post('/users', [UserController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('users.store');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('users.destroy');
