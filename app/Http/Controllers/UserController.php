<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => ['required', Rule::in(['admin', 'petugas'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);

        Log::info('API: User created', [
            'created_user_id' => $user->id,
            'role' => $user->role,
            'ip' => $request->ip(),
            'by_user_id' => $request->user() ? $request->user()->id : null
        ]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'role' => ['sometimes', 'required', Rule::in(['admin', 'petugas'])],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        Log::info('API: User updated', [
            'updated_user_id' => $user->id,
            'ip' => $request->ip(),
            'by_user_id' => $request->user() ? $request->user()->id : null
        ]);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $userId = $user->id;
        $user->delete();
        
        Log::info('API: User deleted', [
            'deleted_user_id' => $userId,
            'ip' => $request->ip(),
            'by_user_id' => $request->user() ? $request->user()->id : null
        ]);

        return response()->json(null, 204);
    }
}
