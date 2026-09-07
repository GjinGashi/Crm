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
        $data=$request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|min:3',
            'description' => 'nullable',
            'status' => 'required|in:pending,in progress,completed,cancelled',
            'due_date' => 'nullable|date',
        ]);
        $task = Task::create($data);
        return $task;
    }

    public function show(Task $task)
    {
        return $task;
    }
    public function update(Request $request, Task $task)
    {
        $data=$request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|min:3',
            'description' => 'nullable',
            'status' => 'required|in:pending,in progress,completed,cancelled',
            'due_date' => 'nullable|date',
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
