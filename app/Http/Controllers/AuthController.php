<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            return back()->withErrors([
                'userId' => 'Invalid credentials. Please check your User ID and Password.',
            ])->withInput(['userId' => $request->userId]);
        }

        Auth::login($user);

        return redirect('/home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);

        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => sha1($request->password),
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
