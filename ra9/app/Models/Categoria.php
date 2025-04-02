<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {
    protected $table = 'categoria'; 
    protected $primaryKey = 'id_categoria'; 
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_categoria', 'descripcion', 'categoria_padre'];

    
    public function parent() {
        return $this->belongsTo(Categoria::class, 'categoria_padre', 'id_categoria');
    }

   
    public function children() {
        return $this->hasMany(Categoria::class, 'categoria_padre', 'id_categoria');
    }
}
