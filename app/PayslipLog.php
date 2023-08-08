<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipLog extends Model
{
    protected $primaryKey = 'id';
     protected $fillable = [
        'user_id','pay_period', 'transaction',
    ];
}
