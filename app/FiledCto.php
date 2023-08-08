<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FiledCto extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'filed_cto';


    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id','supervisor_id','hr_id','sub_signatory_id','signatory_id','credits_id','leave_details','leave_type',
        'inclusive_dates','no_days','start_date','end_date','status','remarks','isarchived','isdisabled','hr_remarks','supervisor_remarks','sub_signatory_remarks','signatory_remarks','date_approved',
        'is_external','external_name','external_designation'
    ];

      //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A CTO has been {$eventName}")
       //set log name to holidays
       ->useLogName('CTO')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }
}
