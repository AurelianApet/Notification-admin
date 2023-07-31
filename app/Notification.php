<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'id',
        'group_id',
        'notification_content',
        'date',
        'title',
        'is_sent',
        'from_id'
    ];
}
