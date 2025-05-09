<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,user_email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'emp_id' => 'nullable|exists:employees,emp_id',
            'user_status_id' => 'nullable|exists:user_status,id',
        ]);

        $user = User::create([
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 3,
            'emp_id' => $validated['emp_id'] ?? null,
            'user_status_id' => 2, //Inactive
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['user_email' => $credentials['user_email'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'user_email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }
        return response()->json(['message' => 'Not authorized'], 401);
    }
    
    
}
