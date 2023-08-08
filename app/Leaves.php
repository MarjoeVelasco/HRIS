<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
	protected $primaryKey = 'leave_id';
    protected $fillable = [
        'leave_id','employee_id','supervisor_id','approver_id','signatory_id','leave_type','details','inclusive_dates','no_days','start_date','end_date','commutation','status','supervisor_note','note','archive','date_recommended','date_approved'
    ];
}
