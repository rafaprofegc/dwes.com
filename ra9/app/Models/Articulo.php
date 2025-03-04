<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = "articulo";
    protected $primaryKey = "referencia";

    public $incrementing = false;
    public $timestamps = false;

    
}
