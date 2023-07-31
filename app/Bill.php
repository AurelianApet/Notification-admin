<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $fillable = [
        'id',
        'user_id',
        'media_balance',
        'media_expired',
        'media_activate',
        'output_balance',
        'output_expired',
        'output_activate',
        'output_user_name'
    ];
}
