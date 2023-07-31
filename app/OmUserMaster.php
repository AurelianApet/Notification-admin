<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OmUserMaster extends Model
{
    //
    public $table = "om_user_master";
    protected $fillable = [
        'user_id',
        'login_name',
        'active'
    ];
}
