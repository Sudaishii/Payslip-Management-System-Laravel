<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayslipStatus;

class PayslipStatusController extends Controller
{
    public function getPayslipStatuses()
    {
        $statuses = PayslipStatus::all();
        return response()->json(['statuses' => $statuses]);
    }

    public function getPayslipStatusById($id)
    {
        $status = PayslipStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'Payslip status not found'], 404);
        }
        return response()->json(['status' => $status]);
    }

    private $defaultStatuses = ['generated', 'released', 'on hold', 'cancelled'];

    public function addPayslipStatus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payslip_statuses,name',
            'description' => 'nullable|string',
        ]);

        if (in_array(strtolower($validated['name']), $this->defaultStatuses)) {
            return response()->json(['message' => 'Cannot add a default payslip status'], 400);
        }

        $status = PayslipStatus::create($validated);

        return response()->json(['message' => 'Payslip status created successfully', 'status' => $status], 201);
    }

    public function editPayslipStatus(Request $request, $id)
    {
        $status = PayslipStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'Payslip status not found'], 404);
        }

        if (in_array(strtolower($status->name), $this->defaultStatuses)) {
            return response()->json(['message' => 'Cannot modify a default payslip status'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payslip_statuses,name,' . $id . ',payslip_status_id',
            'description' => 'nullable|string',
        ]);

        $status->update($validated);

        return response()->json(['message' => 'Payslip status updated successfully', 'status' => $status]);
    }

    public function deletePayslipStatus($id)
    {
        $status = PayslipStatus::find($id);
        if (!$status) {
            return response()->json(['message' => 'Payslip status not found'], 404);
        }

        if (in_array(strtolower($status->name), $this->defaultStatuses)) {
            return response()->json(['message' => 'Cannot delete a default payslip status'], 400);
        }

        $status->delete();

        return response()->json(['message' => 'Payslip status deleted successfully']);
    }
}
