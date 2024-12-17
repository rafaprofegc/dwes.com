<?php

namespace orm\modelo;

use orm\entidad\Articulo;

class ORMArticulo extends ORMBase {
    protected string $tabla = "articulo";
    protected string $clave_primaria = "referencia";

    public function getClaseEntidad() {
        return Articulo::class;
    }
}