<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['special_date', 'time_in', 'time_out', 'remarks'];
}
