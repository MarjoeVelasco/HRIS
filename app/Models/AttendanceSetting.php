<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AttendanceSetting extends Model
{
    use HasFactory;

	protected $primaryKey = 'id';
     protected $fillable = [
        'system_setting_name', 'system_setting_value', 'notes',
    ];
}
