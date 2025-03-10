<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionEnvio extends Model
{
    //
    protected $primaryKey = ['nif', 'id_dir_env'];
    protected $keyType = "array";

    public $incrementing = false;
    public $timestamps = false;

}

