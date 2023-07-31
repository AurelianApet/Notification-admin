<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BdAsistencias extends Model
{
    //
    protected $connection = "mysql2";

    protected $fillable = [
        'id_asistencia',
        'id_instructor',
        'nombre'
    ];
}
