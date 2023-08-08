<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Deductions extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
     protected $fillable = [
         'payslip_id',
         'gsis_insurance',
         'gsis_policy_loan',
         'tax',
         'philhealth_contri',
         'philhealth_diff',
         'gsis_conso',
         'gsis_emergency',
         'gsis_computer',
         'gsis_ins_diff',
         'pagibig_contri',
         'pagibig_mp',
         'pagibig_cal',
         'pagibig_mp2',
         'gsis_educ',
         'gfal',
         'union_dues',
         'paluwagan_shares',
         'ilsea_loan',
         'paluwagan_loan',
         'total_deduction',

    ];

    //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A Deduction has been {$eventName}")
       //set log name to holidays
       ->useLogName('Payslip Deductions')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }

}
