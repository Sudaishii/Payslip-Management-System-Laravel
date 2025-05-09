<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;

class RolesController extends Controller
{
    protected $predefinedRoles = [
        'super admin',
        'human resource admin',
        'employee',
        'staff',
    ];

    public function getRoles()
    {
        $roles = Roles::all();
        return response()->json(['roles' => $roles]);
    }

    public function getRoleById($id)
    {
        $role = Roles::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json(['role' => $role]);
    }

    public function addRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Roles::create($validated);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function editRole(Request $request, $id)
    {
        $role = Roles::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if (in_array(strtolower($role->name), $this->predefinedRoles)) {
            return response()->json(['message' => 'Cannot modify predefined role'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role->update($validated);

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }

    public function deleteRole($id)
    {
        $role = Roles::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if (in_array(strtolower($role->name), $this->predefinedRoles)) {
            return response()->json(['message' => 'Cannot delete predefined role'], 400);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
