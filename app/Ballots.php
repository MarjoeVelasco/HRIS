<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ballots extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = ['id','reference_number','ballot_number','form_id','category_id','user_id'];

    public function getActivitylogOptions(): LogOptions {
         return LogOptions::defaults()
         ->setDescriptionForEvent(fn(string $eventName) => "A ballot has been {$eventName}")
         ->useLogName('Ballots')
         ->logOnlyDirty()
         ->dontSubmitEmptyLogs();
     }
}
