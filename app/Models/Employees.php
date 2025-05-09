<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_id';

    protected $fillable = [
        'emp_fname',
        'emp_middle',
        'emp_lname',
        'emp_age',
        'emp_sex',
        'emp_add',
        'emp_email',
        'emp_contact',
        'emp_hdate',
        'emp_dept',
        'emp_position',
        'rates_id',
    ];

    protected $casts = [
        'emp_age' => 'integer',
        'emp_hdate' => 'date',
        'rates_id' => 'integer',
    ];

    public $timestamps = true;
}
