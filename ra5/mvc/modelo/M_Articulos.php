<?php
namespace mvc\modelo;

use mvc\modelo\Modelo;
use orm\modelo\ORMArticulo;

class M_Articulos implements Modelo {
    
    public function despacha(): mixed {
        $orm_articulos = new ORMArticulo();
        $datos = $orm_articulos->getAll();
        return $datos;
    }
}
?>