<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Get all roles for a specific user
     */
    public function getRoles($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        $roles = $user->getRoleNames();
        return response()->json([
            'success' => true,
            'roles' => $roles,
        ], 200);
    }
    /**
     * Get all users with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $users = User::orderBy('id', 'desc')->paginate($per_page);
        // Attach roles to each user
        $users->getCollection()->transform(function ($user) {
            $user->roles = $user->getRoleNames();
            return $user;
        });
        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    /**
     * Store new user
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'division' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Get specific user record
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    /**
     * Update user data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        $validated = $request->validate([
            'employee_id' => 'sometimes|string|max:50',
            'name' => 'sometimes|string|max:255',
            'division' => 'sometimes|string|max:100',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    /**
     * Delete user (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }
        public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['role' => 'required|string|exists:roles,name']);
        $user->assignRole($request->role);
        return response()->json(['message' => 'Role assigned']);
    }

    public function removeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['role' => 'required|string|exists:roles,name']);
        $user->removeRole($request->role);
        return response()->json(['message' => 'Role removed']);
    }
}
