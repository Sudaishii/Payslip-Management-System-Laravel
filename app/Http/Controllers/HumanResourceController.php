<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Rates;

class HumanResourceController extends Controller
{
    public function getEmployees()
    {
        $employees = Employees::all();
        return response()->json(['employees' => $employees]);
    }

    

    public function addEmployee(Request $request)
    {
        $validated = $request->validate([
            'emp_fname' => 'required|string|max:50',
            'emp_middle' => 'nullable|string|max:255',
            'emp_lname' => 'required|string|max:50',
            'emp_age' => 'required|integer',
            'emp_sex' => 'required|string|max:255',
            'emp_add' => 'required|string|max:50',
            'emp_email' => 'required|string|email|max:50',
            'emp_contact' => 'required|string|max:50',
            'emp_hdate' => 'required|date',
            'emp_dept' => 'required|string|max:50',
            'emp_position' => 'required|string|max:50',
        ]);

        $rate = Rates::where('position', $validated['emp_position'])->first();

        if (!$rate) {
            return response()->json(['message' => 'Rate not found for the given position'], 422);
        }

        $validated['rates_id'] = $rate->id ?? $rate->rates_id ?? null;

        if (!$validated['rates_id']) {
            return response()->json(['message' => 'Rate ID not found for the given position'], 422);
        }

        $employee = Employees::create($validated);

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee]);
    }

    public function editEmployee(Request $request, $id)
    {
        $employee = Employees::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $validated = $request->validate([
            'emp_fname' => 'required|string|max:50',
            'emp_middle' => 'nullable|string|max:255',
            'emp_lname' => 'required|string|max:50',
            'emp_age' => 'required|integer',
            'emp_sex' => 'required|string|max:255',
            'emp_add' => 'required|string|max:50',
            'emp_email' => 'required|string|email|max:50',
            'emp_contact' => 'required|string|max:50',
            'emp_hdate' => 'required|date',
            'emp_dept' => 'required|string|max:50',
            'emp_position' => 'required|string|max:50',
        ]);

        $rate = Rates::where('position', $validated['emp_position'])->first();

        if (!$rate) {
            return response()->json(['message' => 'Rate not found for the given position'], 422);
        }

        $validated['rates_id'] = $rate->id ?? $rate->rates_id ?? null;

        if (!$validated['rates_id']) {
            return response()->json(['message' => 'Rate ID not found for the given position'], 422);
        }

        $employee->update($validated);

        return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee]);
    }

    public function deleteEmployee($id)
    {
        $employee = Employees::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
