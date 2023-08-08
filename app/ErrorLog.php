<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $primaryKey = 'id';
     protected $fillable = [
        'message','file', 'line', 'url',
    ];
}
