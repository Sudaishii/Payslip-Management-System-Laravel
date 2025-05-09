<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersStatus extends Model
{
    use HasFactory;

    protected $table = 'user_status';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
