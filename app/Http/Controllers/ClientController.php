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
        if ($request->boolean('archived')) {
            return Client::whereNotNull('archived_at')->get();
        }

        return Client::whereNull('archived_at')->get();
    }

    public function store(Request $request): Client
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
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
            'name' => ['required', 'string', 'max:255'],
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

        return $client;
    }

    public function archive(Client $client): JsonResponse
    {
        $client->update([
            'archived_at' => now(),
        ]);

        return response()->json([
            'message' => 'Client archived successfully',
        ]);
    }

    public function restore(Client $client): JsonResponse
    {
        $client->update([
            'archived_at' => null,
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
