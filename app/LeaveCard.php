<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LeaveCard extends Model
{
    use LogsActivity;
    protected $primaryKey = 'id';
    protected $fillable = [
       'user_id','total_vl','total_sl', 'hours_earned',
   ];

   //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A Leave Card has been {$eventName}")
       //set log name to holidays
       ->useLogName('Leave Card')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }

}
