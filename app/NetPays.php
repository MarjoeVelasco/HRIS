<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NetPays extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
        'payslip_id',
        'netpay7',
        'netpay15',
        'netpay22',
        'netpay30',
        'total_netpay',
    ];

    //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A Net Pay has been {$eventName}")
       //set log name to holidays
       ->useLogName('Payslip Net Pay')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }
}
