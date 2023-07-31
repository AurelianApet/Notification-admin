<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillRecord extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'description',
        'media_attach',
        'output_attach',
        'media_balance',
        'media_expired',
        'output_balance',
        'output_expired'
    ];
    
    public $timestamps = true;
}
