<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    //
    protected $fillable = [
        'id',
        'name',
        'pwd',
        'group_id',
        'lat',
        'lng',
        'position_update_time',
        'is_allow',
        'token',
        'platform',
        'model',
        'number',
    ];
}
