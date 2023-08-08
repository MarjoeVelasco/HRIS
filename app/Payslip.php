<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payslip extends Model
{
    use LogsActivity;
   

    protected $primaryKey = 'id';
     protected $fillable = [
        'employee_id',
        'ref_no',
        'pay_period',
    ];


     //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A Payslip has been {$eventName}")
       //set log name to holidays
       ->useLogName('Payslip General')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }
    
}
