<?php
namespace mvc\modelo\orm;

use orm\modelo\ORMArticulo;
use orm\entidad\Articulo;

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

    public function get_articulos_descripcion(string $descripcion ): array {
        $sql = "SELECT referencia, descripcion, pvp, dto_venta, categoria, ";
        $sql.= "und_disponibles, und_vendidas, fecha_disponible, tipo_iva ";
        $sql.= "FROM {$this->tabla} ";
        $sql.= "WHERE lower(descripcion) LIKE lower(:descripcion)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':descripcion', strtolower('%' . $descripcion . '%'));
        $articulos = [];
        if( $stmt->execute() ) {
            while( $articulo = $stmt->fetch() ) {
                $articulo = new Articulo($articulo);
                $articulos[] = $articulo;
            }
            return $articulos;
        }

        return [];


    }
}
?>