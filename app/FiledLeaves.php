<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FiledLeaves extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id','supervisor_id','hr_id','sub_signatory_id','signatory_id','credits_id','leave_type',
        'isleavetype_others','isvacation_slp','vacation_slp_details','issick','sick_details',
        'isslbw','slbw_details','isstudy','study_details','isother','other_details',
        'inclusive_dates','no_days','start_date','end_date','commutation','status','remarks','isarchived','isdisabled','hr_remarks','supervisor_remarks','sub_signatory_remarks','signatory_remarks','date_approved',
        'internal_notes','leave_remarks','is_external','external_name','external_designation'
    ];

    //log changes in model
   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
       //set description of log
       ->setDescriptionForEvent(fn(string $eventName) => "A Leave has been {$eventName}")
       //set log name to holidays
       ->useLogName('Leave')
       //log only the changes
       ->logOnlyDirty()
       //prevent logging of empty logs
       ->dontSubmitEmptyLogs();
   }
}
