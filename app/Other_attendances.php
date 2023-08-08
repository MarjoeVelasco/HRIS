<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Other_attendances extends Model
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
       ->setDescriptionForEvent(fn(string $eventName) => "Holidays/Weekends Attendances has been {$eventName}")
       //set log name to holidays
       ->useLogName('Holidays/Weekends')
       //set properties to log when creating new holiday
       ->logOnly(['employee_id','status','accomplishment','time_in','time_out'])
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }

}
