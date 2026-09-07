<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }
    public function store(Request $request)
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

    public function show(Client $client)
    {
        return $client->load('projects');
    }
    public function update(Request $request, Client $client)
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
    public function destroy(Client $client)
    {
        try {
            $client->delete();

            return response()->json([
                'message' => 'Client deleted successfully'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'This client cannot be deleted because they have related projects or tasks.'
            ], 409);
        }
    }
}
