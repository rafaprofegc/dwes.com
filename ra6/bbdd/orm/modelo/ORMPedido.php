<?php
namespace orm\modelo;

use orm\entidad\Pedido;
use orm\modelo\ORMBase;

class ORMPedido extends ORMBase {
    protected string $tabla = "pedido";
    protected string $clave_primaria = "npedido";

    public function getClaseEntidad() {
        return Pedido::class;
    }
}
?>