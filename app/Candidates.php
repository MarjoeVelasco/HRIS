<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Candidates extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','form_id','category_id'];


    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "A candidate has been {$eventName}")
        ->useLogName('Candidates')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

}
