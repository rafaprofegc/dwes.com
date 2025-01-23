<?php
namespace rpc\modelo;

use orm\modelo\ORMArticulo;
use orm\entidad\Articulo;

// Leer artículos de la BBDD y enviarlos 
// como servicio RPC

/* Formato de la petición:
{
    "jsonrpc" : "2.0",
    "method" : "RpcOrmArticulo.obtenerArticulo",
    "params" : ["ACIN0003"],
    "id": 1234
}
*/

/*
class RpcOrmArticulo {

    // La petición tendrá: method: RpcOrmArticulo.obtenerArticulo
    public function obtenerArticulo(string $referencia): object {
        $orm_articulo = new ORMArticulo();
        $articulo = $orm_articulo->get($referencia);
        return $articulo;
    }
}
*/
// Puedo exponer los métodos de ORMArticulo directamente
// si extiendo esta clase:
// - get()
// - getAll()
// - insert($articulo)
// - update($id, $articulo)

/* Formato de la petición: método get

{
    "jsonrpc" : "2.0",
    "method" : "RpcOrmArticulo.get",
    "params" : ["ACIN0003"],
    "id": 1234
}
*/

/* Formato de la petición: método get

{
    "jsonrpc" : "2.0",
    "method" : "RpcOrmArticulo.getAll",
    "id": 1234
}
*/

class RpcOrmArticulo extends ORMArticulo { 
    public function getArticulosCategoria($categoria) {
        $sql = "SELECT referencia, descripcion, pvp, dto_venta, und_disponibles, und_vendidas, ";
        $sql.= "fecha_disponible, categoria, tipo_iva ";
        $sql.= "FROM articulo ";
        $sql.= "WHERE categoria = :categoria";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":categoria", $categoria);
        $articulos = [];
        if( $stmt->execute() ) {
            while( $articulo = $stmt->fetch() ) {
                $articulos[] = $articulo;
            }
        }
        return $articulos;
    }
    
    public function getClasificacion($articulo): float {
        
    }
}
?>