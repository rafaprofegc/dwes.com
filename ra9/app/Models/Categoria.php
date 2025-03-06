<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categoria";
    protected $primaryKey = "id_categoria";
    public $incrementing = false;
    public $timestamps = false;
    
}
