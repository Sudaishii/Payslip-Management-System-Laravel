<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTimeRecord extends Model
{
    use HasFactory;

    protected $table = 'dailytimerecords';

    protected $primaryKey = 'record_id';

    protected $fillable = [
        'employee_id',
        'entry_date',
        'time_in',
        'time_out',
        'month',
        'hours_worked',
        'overtime_hrs',
        'absent',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}
