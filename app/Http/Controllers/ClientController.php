<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @return Collection<int, Client>
     */
   public function index(Request $request): Collection
{
    $query = Client::query();

    if ($request->boolean('archived')) {
        $query->whereNotNull('archived_at');
    } else {
        $query->whereNull('archived_at');
    }

    if ($request->filled('search')) {
        $search = $request->string('search')->toString();

        $query->where(function ($query) use ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status') && $request->status !== 'All') {
        $query->where('status', $request->status);
    }

    return $query->get();
}

    public function store(Request $request): Client
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Active,Inactive,Lead,Archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $client = Client::create($data);

        return $client;
    }

    public function show(Client $client): Client
    {
        return $client->load('projects');
    }

    public function update(Request $request, Client $client): Client
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Active,Inactive,Lead,Archived'],
            'notes' => ['nullable', 'string'],
        ]);
        $client->update($data);

        return $client->load('projects');
    }

    public function archive(Client $client): JsonResponse
    {
        $client->update([
            'archived_at' => now(),
            'status' => 'Archived',
        ]);

        return response()->json([
            'message' => 'Client archived successfully',
        ]);
    }

    public function restore(Client $client): JsonResponse
    {
        $client->update([
            'archived_at' => null,
            'status' => 'Active',
        ]);

        return response()->json([
            'message' => 'Client restored successfully',
        ]);
    }

    public function destroy(Client $client): JsonResponse
    {
        try {
            $client->delete();

            return response()->json([
                'message' => 'Client deleted successfully',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'This client cannot be deleted because they have related projects or tasks.',
            ], 409);
        }
    }
}
