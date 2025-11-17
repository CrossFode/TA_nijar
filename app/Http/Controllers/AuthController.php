<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register user securely
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Registration successful'
        ], 201);
    }


    /**
     * Login user securely
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        // Verify credentials securely
        if (!Auth::attempt($validated)) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        // Delete old tokens (optional security)
        $request->user()->tokens()->delete();

        $token = $request->user()->createToken('api')->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'token'      => $token
        ], 200);
    }
}
