<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WorkSetting extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
       'attendance_id','status',
   ];

    //log changes in model
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        //set description of log
        ->setDescriptionForEvent(fn(string $eventName) => "An Work Setting has been {$eventName}")
        //set log name to holidays
        ->useLogName('Work Setting')
        //log only the changes
        ->logOnlyDirty()
        //prevent logging of empty logs
        ->dontSubmitEmptyLogs();
    }
}
