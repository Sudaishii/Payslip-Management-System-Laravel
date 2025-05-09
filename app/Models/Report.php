<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employees;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'emp_id',
        'month',
        'year',
        'total_hours',
        'total_overtime',
        'gross_salary',
        'sss',
        'phil_health',
        'pag_ibig',
        't_deductions',
        'overtime_pay',
        'net_pay',
        'payslip_status_id',
        'date_generated',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'emp_id');
    }

    public function payslipStatus()
    {
        return $this->belongsTo(PayslipStatus::class, 'payslip_status_id', 'payslip_status_id');
    }
}
