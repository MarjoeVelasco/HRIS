<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
     protected $fillable = [
        'employee_id','employee_number', 'item_number', 'prefix','lastname','firstname','middlename','extname','position','sg','stepinc','division','unit','status','shift','birthday','signature',
    ];

     //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "An Employee has been {$eventName}")
       //set log name to holidays
       ->useLogName('Employees')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }
}
