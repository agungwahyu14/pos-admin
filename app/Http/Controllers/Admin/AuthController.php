<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Find user by name
        $user = \App\Models\User::where('name', $request->name)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            Log::warning('Admin Login Failed: Invalid credentials', [
                'attempted_name' => $request->name,
                'ip' => $request->ip()
            ]);
            return back()->withErrors([
                'name' => 'The provided credentials do not match our records.',
            ])->onlyInput('name');
        }

        if ($user->role !== 'admin') {
            Log::warning('Admin Login Failed: Unauthorized role', [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'ip' => $request->ip()
            ]);
            return back()->withErrors([
                'name' => 'Access denied. Only admins can login here.',
            ])->onlyInput('name');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        Log::info('Admin Login Successful', [
            'user_id' => $user->id,
            'name' => $user->name,
            'ip' => $request->ip()
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            Log::info('Admin Logout Successful', [
                'user_id' => $user->id,
                'name' => $user->name,
                'ip' => $request->ip()
            ]);
        }

        return redirect()->route('admin.login');
    }
}
