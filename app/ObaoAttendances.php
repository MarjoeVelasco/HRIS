<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ObaoAttendances extends Model
{
    use LogsActivity;

    protected $primaryKey = 'attendance_id';
    protected $fillable = [
       'attendance_id', 'status', 'time_status', 'employee_id','time_in','time_out','accomplishment','hours_worked',
   ];

    //log changes in model
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        //set description of log
        ->setDescriptionForEvent(fn(string $eventName) => "An OB/AO Attendance has been {$eventName}")
        //set log name to holidays
        ->useLogName('OB/AO Attendance')
        //log only the changes
        ->logOnlyDirty()
        //prevent logging of empty logs
        ->dontSubmitEmptyLogs();
    }
}
