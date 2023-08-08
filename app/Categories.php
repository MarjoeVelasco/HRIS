<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Categories extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    protected $fillable = ['id','title','description','status','is_archive'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "A category has been {$eventName}")
        ->useLogName('Categories')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

}
