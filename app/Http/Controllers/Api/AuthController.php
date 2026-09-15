<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (! auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided details are incorrect',
            ], 401);
        }
        $user = auth()->user();
        $token = $user->createToken('crm-api')->plainTextToken;
        auth()->logout();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email,'.$request->user()->id,
            ],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            if (empty($data['current_password'])) {
                return response()->json([
                    'errors' => [
                        'current_password' => [
                            'Current Password is required to change your password',
                        ],
                    ],
                ], 422);
            }

            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    }
}
