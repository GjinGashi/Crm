<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Todo,In Progress,Completed,Cancelled'],
            'priority' => ['required', 'string', 'in:Low,Medium,High,Urgent'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'due_date' => ['nullable', 'date'],
        ]);
        $task = Task::create($data);
        return $task;
    }

    public function show(Task $task)
    {
        return $task->load('project', 'user');
    }
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Todo,In Progress,Completed,Cancelled'],
            'priority' => ['required', 'string', 'in:Low,Medium,High,Urgent'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'due_date' => ['nullable', 'date'],
        ]);
        $task->update($data);
        return $task;
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task Deleted']);
    }
}
