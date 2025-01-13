<?php
namespace mvc\modelo\orm;

use orm\modelo\ORMArticulo;

class Mvc_Orm_Articulos extends ORMArticulo {
    
    public function get_ofertas(): array {
        $sql = "select referencia, descripcion, pvp, dto_venta ";
        $sql.= "from articulo ";
        $sql.= "where dto_venta = (select max(dto_venta) from articulo)";

        $stmt = $this->pdo->prepare($sql);
        if( $stmt->execute() ) {
            $articulos = $stmt->fetchAll();
            return $articulos;
        }
        else {
            return [];
        }
    }
}
?>