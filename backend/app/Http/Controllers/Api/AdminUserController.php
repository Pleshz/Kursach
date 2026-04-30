<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => User::query()
                ->orderByDesc('id')
                ->get(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'data' => $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'client'])],
        ]);

        $user = User::query()->create($validated);

        return response()->json([
            'data' => $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'required', Rule::in(['admin', 'client'])],
        ]);

        if (array_key_exists('password', $validated) && !$validated['password']) {
            unset($validated['password']);
        }

        $user->fill($validated);
        $user->save();

        return response()->json([
            'data' => $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Нельзя удалить текущего администратора.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Пользователь удален.',
        ]);
    }
}

