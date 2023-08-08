<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LeaveCredits extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
       'total_vl','total_sl', 'less_vl', 'less_sl', 'balance_vl', 'balance_sl', 'date_certification',
   ];

     //log changes in model
     public function getActivitylogOptions(): LogOptions
     {
         return LogOptions::defaults()
         //set description of log
         ->setDescriptionForEvent(fn(string $eventName) => "A Leave Credits has been {$eventName}")
         //set log name to holidays
         ->useLogName('Leave Credits')
         //log only the changes
         ->logOnlyDirty()
         //prevent logging of empty logs
         ->dontSubmitEmptyLogs();
     }

}
