<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Compensations extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
     protected $fillable = [
         'payslip_id',
         'basic_pay',
         'representation',
         'transportation',
         'rep_trans_sum',
         'gross_pay',
         'pera',
         'salary_differential',
         'myb_adjustments',
         'lates_undertime',
         'pera_under_diff',
         'gross_income',

    ];

    //log changes in model
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        //set description of log
        ->setDescriptionForEvent(fn(string $eventName) => "A Compensation has been {$eventName}")
        //set log name to holidays
        ->useLogName('Payslip Compensations')
        //log only the changes
        ->logOnlyDirty()
        //prevent logging of empty logs
        ->dontSubmitEmptyLogs();
    }

}
