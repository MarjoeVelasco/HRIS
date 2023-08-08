<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Attendance extends Model
{
    use LogsActivity;

	protected $primaryKey = 'attendance_id';
     protected $fillable = [
        'attendance_id', 'status', 'time_status', 'employee_id','time_in','time_out','late','accomplishment','undertime','overtime','hours_worked',
    ];


    //log changes in model
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "An attendance has been {$eventName}")
        ->useLogName('Attendances')
        ->logOnly(['employee_id','status','accomplishment','time_in','time_out'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

}

class DateRange extends Model
{

    protected $primaryKey = 'attendance_id';
    protected $fillable = [
        'attendance_id', 'date_from', 'date_to'
    ];
}

?>