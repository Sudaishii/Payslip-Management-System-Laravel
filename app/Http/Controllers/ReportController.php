<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;


class ReportController extends Controller
{
    /**
     * Delete a report by report_id.
     */
    public function deleteReport($reportId)
    {
        $report = Report::find($reportId);
        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

   
    public function createReport(Request $request)
{
    $data = $request->all();

    // If 'payslip_status_id' is null or not set, default to 1
    if (empty($data['payslip_status_id'])) {
        $data['payslip_status_id'] = 1;
    }

    $report = Report::create($data);

    return response()->json($report, 201);
}


    /**
     * Get all payslip reports with related employee and payslip status.
     */
    public function getAllPayslipReports()
    {
        $reports = Report::with(['employee', 'payslipStatus'])->get();
        return response()->json($reports);
    }

    /**
     * Get a specific payslip report by report_id with related data.
     */
    public function getPayslipReport($reportId)
    {
        $report = Report::with(['employee', 'payslipStatus'])->find($reportId);
        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }
        return response()->json($report);
    }

    /**
     * Get payslip reports filtered by employee ID with related employee and payslip status.
     */
    public function getPayslipReportsByEmployeeId($employeeId)
    {
        $reports = Report::with(['employee', 'payslipStatus'])
            ->where('employee_id', $employeeId)
            ->get();

        return response()->json($reports);
    }
}
