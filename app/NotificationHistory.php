<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationHistory extends Model
{
    //
    protected $fillable = [
        'id',
        'user_id',
        'notification_id',
    ];
}
