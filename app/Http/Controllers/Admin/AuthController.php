<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return back()->withErrors([
                'name' => 'The provided credentials do not match our records.',
            ])->onlyInput('name');
        }

        if ($user->role !== 'admin') {
            return back()->withErrors([
                'name' => 'Access denied. Only admins can login here.',
            ])->onlyInput('name');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
