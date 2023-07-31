<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BdInstructore extends Model
{
    //
    protected $connection = 'mysql2';
    protected $fillable = [
        'id_instructor',
        'usuario_trascendental',
    ];

}
