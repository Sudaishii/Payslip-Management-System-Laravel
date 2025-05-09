<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['user' => $user]);
    }

    public function addUser(Request $request)
    {
        $input = $request->all();

        // Handle nullable foreign keys
        $input['role_id'] = $input['role_id'] ?? null;
        $input['role_id'] = $input['role_id'] === '' ? null : $input['role_id'];

        $input['emp_id'] = $input['emp_id'] ?? null;
        $input['emp_id'] = $input['emp_id'] === '' ? null : $input['emp_id'];

        // Set default status (e.g., Active = 1)
        $input['user_status_id'] = 1;

        // Pluck the actual ID columns from reference tables
        $roleIds = DB::table('roles')->pluck('id')->toArray();
        $empIds = DB::table('employees')->pluck('emp_id')->toArray();
        $userStatusIds = DB::table('user_status')->pluck('id')->toArray();

        // Validate request
        $validated = validator($input, [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email',
            'password' => 'required|string|min:8',
            'role_id' => ['nullable', 'integer', Rule::in($roleIds)],
            'emp_id' => ['nullable', 'integer', Rule::in($empIds)],
            'user_status_id' => ['required', 'integer', Rule::in($userStatusIds)],
        ])->validate();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $input = $request->all();

        // Set nullable fields properly
        $input['role_id'] = $input['role_id'] ?? null;
        $input['role_id'] = $input['role_id'] === '' ? null : $input['role_id'];

        $input['emp_id'] = $input['emp_id'] ?? null;
        $input['emp_id'] = $input['emp_id'] === '' ? null : $input['emp_id'];

        $roleIds = DB::table('roles')->pluck('id')->toArray();
        $empIds = DB::table('employees')->pluck('emp_id')->toArray();
        $userStatusIds = DB::table('user_status')->pluck('id')->toArray();

        $validated = validator($input, [
            'user_name' => 'sometimes|required|string|max:255',
            'user_email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($user->id, 'id'),
            ],
            'password' => 'sometimes|required|string|min:6',
            'role_id' => ['nullable', 'integer', Rule::in($roleIds)],
            'emp_id' => ['nullable', 'integer', Rule::in($empIds)],
            'user_status_id' => ['sometimes', 'required', 'integer', Rule::in($userStatusIds)],
        ])->validate();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
