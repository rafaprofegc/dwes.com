<?php
namespace rest\modelo;

use rest\modelo\RestORMBase;

class RestORMArticulo extends RestORMBase {
    protected string $tabla = "articulo";
    protected string $clave_primaria = "referencia";

    public function getClaseEntidad(): string {
        return "";
    }
}
?>