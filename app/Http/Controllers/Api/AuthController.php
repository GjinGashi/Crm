<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
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
}
