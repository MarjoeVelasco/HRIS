<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Obao extends Model
{

    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
       'id','employee_id','status','inclusive_dates','start_time','end_time','title','details','note'
   ];

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        //set description of log
        ->setDescriptionForEvent(fn(string $eventName) => "An OBAO has been {$eventName}")
        //set log name to holidays
        ->useLogName('OBAO')
        //set properties to log when creating new holiday
        ->logOnly(['status','employee_id','title','inclusive_dates'])
        //log only the changes
        ->logOnlyDirty()
        //prevent logging of empty logs
        ->dontSubmitEmptyLogs();
    }


}
