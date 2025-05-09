<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyTimeRecord;
use App\Models\Employees;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DailyTimeRecordController extends Controller
{
    public function showUploadForm()
    {
        // Removed as per user request - HTML upload form not necessary
        return response()->json(['message' => 'Upload form not available. Use POST /api/import-daily-time-records with csv_file form-data.'], 404);
    }

    /**
     * Generate report for an employee for a given month and year.
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'emp_id' => 'required|integer|exists:employees,emp_id',
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $empId = $request->input('emp_id');
        $month = $request->input('month');
        $year = $request->input('year');

        // Fetch daily time records for the employee for the given month and year
        $records = DailyTimeRecord::where('employee_id', $empId)
            ->where('month', $month)
            ->whereYear('entry_date', $year)
            ->get();

        if ($records->isEmpty()) {
            return response()->json(['message' => 'No daily time records found for the specified employee and month/year.'], 404);
        }

        // Calculate total hours and total overtime
        $totalHours = $records->sum('hours_worked');
        $totalOvertime = $records->sum('overtime_hrs');

        // Assume hourly rate (for example, 100 PHP per hour) - this can be adjusted or fetched from employee data
        $hourlyRate = 100;

        $grossSalary = $totalHours * $hourlyRate;
        $overtimePay = $totalOvertime * ($hourlyRate * 1.25); // 25% overtime premium

        // Default rates
        $pagIbig = 200.00;
        $sss = $grossSalary * 0.05;
        $philHealth = $grossSalary * 0.025;

        $totalDeductions = $pagIbig + $sss + $philHealth;
        $netPay = $grossSalary + $overtimePay - $totalDeductions;

        // Get payslip_status_id for "on hold"
        $onHoldStatus = \App\Models\PayslipStatus::where('name', 'generated')->first();

        $payslipStatusId = $onHoldStatus ? $onHoldStatus->payslip_status_id : null;

        // Save or update report
        $report = \App\Models\Report::updateOrCreate(
            [
                'emp_id' => $empId,
                'month' => $month,
                'year' => $year,
            ],
            [
                'total_hours' => $totalHours,
                'total_overtime' => $totalOvertime,
                'gross_salary' => $grossSalary,
                'sss' => $sss,
                'phil_health' => $philHealth,
                'pag_ibig' => $pagIbig,
                't_deductions' => $totalDeductions,
                'overtime_pay' => $overtimePay,
                'net_pay' => $netPay,
                'payslip_status_id' => $payslipStatusId,
                'date_generated' => now(),
            ]
        );

        return response()->json(['message' => 'Report generated successfully', 'report' => $report]);
    }

    /**
     * Import Daily Time Records from uploaded CSV file.
     */
    public function importCsv(Request $request)
    {
        // Check if CSV file is uploaded
        if ($request->hasFile('csv_file')) {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            return $this->importFromPath($path);
        } else {
            // If no CSV file, try to add single record from request data
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|integer|exists:employees,emp_id',
                'entry_date' => 'required|date',
                'time_in' => ['required', 'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
                'time_out' => ['required', 'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
                'month' => 'required|string',
                'hours_worked' => 'required|numeric',
                'overtime_hrs' => 'required|numeric',
                'absent' => ['required', 'in:Yes,No'],
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $record = DailyTimeRecord::create([
                'employee_id' => $request->employee_id,
                'entry_date' => $request->entry_date,
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'month' => $request->month,
                'hours_worked' => $request->hours_worked,
                'overtime_hrs' => $request->overtime_hrs,
                'absent' => $request->absent,
            ]);

            return response()->json(['message' => 'Daily Time Record added successfully', 'record' => $record], 201);
        }
    }

  
    public function importFromLocal()
    {
        $path = storage_path('app/uploads/DailyTimeRecords.csv');

        if (!File::exists($path)) {
            return response()->json(['message' => 'CSV file not found at ' . $path], 404);
        }

        return $this->importFromPath($path);
    }

    /**
     * Common import logic from CSV file path.
     */
    private function importFromPath($path)
    {
        $file_handle = fopen($path, 'r');
        if (!$file_handle) {
            return response()->json(['message' => 'Unable to open the file'], 500);
        }

        // Skip empty lines before header
        do {
            $header = fgetcsv($file_handle);
        } while ($header !== false && count(array_filter($header)) === 0);

        $expectedHeader = ['employee_id', 'entry_date', 'time_in', 'time_out', 'month', 'hours_worked', 'overtime_hrs', 'absent'];

        if ($header === false) {
            fclose($file_handle);
            return response()->json(['message' => 'CSV file is empty or invalid'], 422);
        }

        // Remove BOM from first header element if present
        if (substr($header[0], 0, 3) === "\xEF\xBB\xBF") {
            $header[0] = substr($header[0], 3);
        }

        // Normalize header: trim spaces and lowercase
        $normalizedHeader = array_map(function($h) {
            return strtolower(trim($h));
        }, $header);

        $normalizedExpected = array_map(function($h) {
            return strtolower(trim($h));
        }, $expectedHeader);

        if ($normalizedHeader !== $normalizedExpected) {
            fclose($file_handle);
            return response()->json(['message' => 'Invalid CSV header. Expected: ' . implode(',', $expectedHeader) . '. Got: ' . implode(',', $header)], 422);
        }

        $records = [];
        $lineNumber = 1;
        $errors = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file_handle)) !== false) {
                $lineNumber++;

                $data = array_combine($header, $row);

                $employeeExists = Employees::where('emp_id', $data['employee_id'])->exists();
                if (!$employeeExists) {
                    $errors[] = "Line $lineNumber: Employee ID {$data['employee_id']} does not exist.";
                    continue;
                }

                // Convert entry_date to Y-m-d format before validation
                if (isset($data['entry_date'])) {
                    $date = \DateTime::createFromFormat('m/d/Y', $data['entry_date']);
                    if ($date) {
                        $data['entry_date'] = $date->format('Y-m-d');
                    }
                }

                $validator = Validator::make($data, [
                    'employee_id' => 'required|integer',
                    'entry_date' => ['required', 'date'],
                    'time_in' => ['required', 'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
                    'time_out' => ['required', 'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
                    'month' => 'required|string',
                    'hours_worked' => 'required|numeric',
                    'overtime_hrs' => 'required|numeric',
                    'absent' => ['required', 'in:Yes,No'],
                ]);

                if ($validator->fails()) {
                    $errors[] = "Line $lineNumber: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $records[] = [
                    'employee_id' => $data['employee_id'],
                    'entry_date' => $data['entry_date'],
                    'time_in' => $data['time_in'],
                    'time_out' => $data['time_out'],
                    'month' => $data['month'],
                    'hours_worked' => $data['hours_worked'],
                    'overtime_hrs' => $data['overtime_hrs'],
                    'absent' => $data['absent'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Skip rollback and error response for missing employees, just skip those records
            if (!empty($errors)) {
                // Remove errors related to missing employees from errors array
                $filteredErrors = array_filter($errors, function($error) {
                    return strpos($error, 'does not exist') === false;
                });

                if (!empty($filteredErrors)) {
                    DB::rollBack();
                    fclose($file_handle);

                    // Get unique employee IDs from successfully validated records
                    $importedEmployeeIds = array_unique(array_column($records, 'employee_id'));

                    return response()->json([
                        'message' => 'Errors found in CSV',
                        'errors' => $filteredErrors,
                        'imported_employee_ids' => $importedEmployeeIds,
                        'imported_records_count' => count($records)
                    ], 422);
                }
            }

            DailyTimeRecord::insert($records);

            DB::commit();
            fclose($file_handle);

            // Get unique employee IDs from imported records
            $importedEmployeeIds = array_unique(array_column($records, 'employee_id'));

            return response()->json([
                'message' => 'CSV imported successfully',
                'imported_records' => count($records),
                'imported_employee_ids' => $importedEmployeeIds
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file_handle);
            return response()->json(['message' => 'Error importing CSV', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve Daily Time Records, optionally filtered by employee_id or month.
     */
    public function getRecords(Request $request)
    {
        $query = DailyTimeRecord::query();

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        if ($request->has('month')) {
            $query->where('month', $request->input('month'));
        }

        $records = $query->get();

        return response()->json(['daily_time_records' => $records]);
    }
}
