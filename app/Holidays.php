<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Holidays extends Model
{
    use LogsActivity;
    
    protected $primaryKey = 'id';
    protected $fillable = [
       'holiday_name','inclusive_dates', 'remarks', 'users',
   ];

    //log changes in model
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        //set description of log
        ->setDescriptionForEvent(fn(string $eventName) => "A holiday has been {$eventName}")
        //set log name to holidays
        ->useLogName('Holidays')
        //set properties to log when creating new holiday
        ->logOnly(['holiday_name','inclusive_dates'])
        //log only the changes
        ->logOnlyDirty()
        //prevent logging of empty logs
        ->dontSubmitEmptyLogs();
    }

}
