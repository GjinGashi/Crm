<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return inertia('Welcome', [
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
})->middleware('auth')->name('home');

Route::inertia('/clients', 'Clients')
    ->middleware('auth')
    ->name('clients');
Route::inertia('/clients/create', 'ClientCreate')
    ->middleware('auth')
    ->name('clients.create');
Route::inertia('/clients/{client}/edit', 'ClientEdit')
    ->middleware('auth')
    ->name('clients.edit');
Route::inertia('/clients/{client}', 'ClientShow')
    ->middleware('auth')
    ->name('clients.details');
Route::inertia('/projects', 'Projects')
    ->middleware('auth')
    ->name('Projects');
Route::get('/projects/create', function () {
    return Inertia::render('ProjectCreate');
})->middleware('auth');
Route::get('/projects/{project}/edit', function () {
    return Inertia::render('ProjectEdit');
})->middleware('auth');
Route::inertia('/projects/{project}', 'ProjectShow')
    ->middleware('auth')
    ->name('projects.details');
Route::inertia('/tasks', 'Tasks')
    ->middleware('auth')
    ->name('Tasks');
Route::inertia('/tasks/create', 'Tasks/CreateTask')
    ->middleware('auth')
    ->name('tasks.create');
Route::inertia('/tasks/{task}', 'TaskShow')
    ->middleware('auth')
    ->name('tasks.details');
Route::get('/tasks/{task}/edit', function (Task $task) {
    return Inertia::render('Tasks/EditTask', [
        'taskId' => $task->id,
    ]);
})->middleware('auth')->name('tasks.edit');
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
