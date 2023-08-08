<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Forms extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = ['id','title','description','status','start_date','end_date','is_archive','internal_note'];

    //log changes in model
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "A form has been {$eventName}")
        ->useLogName('Forms')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
