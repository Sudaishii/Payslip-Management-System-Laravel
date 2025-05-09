<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersStatus;

class UserStatusController extends Controller
{
    public function getUserStatus()
    {
        $statuses = UsersStatus::all();
        return response()->json(['statuses' => $statuses]);
    }

    public function getUserStatusById($id)
    {
        $status = UsersStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'User status not found'], 404);
        }
        return response()->json(['status' => $status]);
    }

    public function addUserStatus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_status,name',
        ]);

        // Only allow adding 'Archived' status
        if (strtolower($validated['name']) !== 'archived') {
            return response()->json(['message' => 'Only "Archived" status can be added'], 400);
        }

        $status = UsersStatus::create($validated);

        return response()->json(['message' => 'User status created successfully', 'status' => $status], 201);
    }

    public function editUserStatus(Request $request, $id)
    {
        $status = UsersStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'User status not found'], 404);
        }

        // Prevent modifying predefined statuses Active and Inactive
        if (in_array(strtolower($status->name), ['active', 'inactive'])) {
            return response()->json(['message' => 'Cannot modify predefined status'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_status,name,' . $id . ',id',
        ]);

        $status->update($validated);

        return response()->json(['message' => 'User status updated successfully', 'status' => $status]);
    }

    public function deleteUserStatus($id)
    {
        $status = UsersStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'User status not found'], 404);
        }

        // Prevent deleting predefined statuses Active and Inactive
        if (in_array(strtolower($status->name), ['active', 'inactive'])) {
            return response()->json(['message' => 'Cannot delete predefined status'], 400);
        }

        $status->delete();

        return response()->json(['message' => 'User status deleted successfully']);
    }
}
