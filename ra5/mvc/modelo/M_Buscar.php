<?php
namespace mvc\modelo;

use mvc\modelo\Modelo;
use mvc\modelo\orm\Mvc_Orm_Articulos;

class M_Buscar implements Modelo {
    public function despacha(): mixed {

        // Sanear la descripción
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);

        $orm_articulos = new Mvc_Orm_Articulos();
        $articulos = $orm_articulos->get_articulos_descripcion($descripcion);
        return $articulos;
    }
}
?>