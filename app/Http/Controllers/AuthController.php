<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        try {
            $user = User::create($validatedData);
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user', 'details' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request): array
    {
        $user = auth('sanctum')->user();

        if ($user) {
            $user->tokens()->delete();
            return [
                'message' => 'user logged out'
            ];
        }

        return [
            'message' => 'No authenticated user'
        ];
    }
}
