<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    //
    protected $fillable = [
        'id',
        'message',
    ];
}
