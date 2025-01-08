<?php
namespace orm\modelo;
use orm\entidad\Cliente;

class ORMCliente extends ORMBase {
    protected string $tabla = "cliente";
    protected string $clave_primaria = "nif";

    public function getClaseEntidad() {
        return Cliente::class;
    }
}

?>