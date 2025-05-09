<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipStatus extends Model
{
    use HasFactory;

    protected $table = 'payslip_statuses';

    protected $primaryKey = 'payslip_status_id';

    protected $fillable = [
        'name',
        'description',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'payslip_status_id', 'payslip_status_id');
    }
}
