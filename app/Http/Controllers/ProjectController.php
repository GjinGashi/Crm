<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @return Collection<int, Project>
     */
    public function index(): Collection
    {
        return Project::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Project
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Planning,In Progress,On Hold,Completed,Cancelled'],
            'priority' => ['required', 'string', 'in:Low,Medium,High,Urgent'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],

        ]);
        $project = Project::create($data);

        return $project;
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): Project
    {
        return $project->load('client', 'tasks');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): Project
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Planning,In Progress,On Hold,Completed,Cancelled'],
            'priority' => ['required', 'string', 'in:Low,Medium,High,Urgent'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
        ]);
        $project->update($data);

        return $project;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted succesfully',
        ]);
    }
}
