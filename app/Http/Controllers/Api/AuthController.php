<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
        ]);

        // Check if userId is email, name, or id
        $user = User::where('email', $request->userId)
            ->orWhere('name', $request->userId)
            ->orWhere('id', $request->userId)
            ->first();

        if (!$user || sha1($request->password) !== $user->password) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your User ID and Password.'
            ], 401);
        }

        // Log the user in (session-based)
        Auth::login($user);

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
