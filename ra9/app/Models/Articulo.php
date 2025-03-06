<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = "articulo";
    protected $primaryKey = "referencia";

    /*
    protected $primaryKey = ['referencia','nif'];
    protected $keyType = 'array';
    */

    public $incrementing = false;
    public $timestamps = false;

    public const FORMATO_FECHA_ES = "d/m/Y";

    public function getFechaDisponibleAttribute($valor) {
        if( $valor ) {
            $fecha = new \DateTime($valor);
            return $fecha->format(self::FORMATO_FECHA_ES);
        }
        else {
            return "Sin disponibilidad";
        }
    }
    
}
