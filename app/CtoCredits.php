<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class CtoCredits extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
       'date_certification','hours_earned', 'last_certification',
   ];

   //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A CTO Credits has been {$eventName}")
       //set log name to holidays
       ->useLogName('CTO Credits')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }

}
