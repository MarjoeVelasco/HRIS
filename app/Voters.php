<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Voters extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = ['id','form_id','ballot_number','user_id'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "A voter has been {$eventName}")
        ->useLogName('Voters')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

}
