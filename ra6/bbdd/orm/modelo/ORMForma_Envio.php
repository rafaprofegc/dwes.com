<?php
namespace orm\modelo;

use orm\entidad\Forma_Envio;
use orm\modelo\ORMBase;

class ORMForma_Envio extends ORMBase {
    protected string $tabla = "forma_envio";
    protected string $clave_primaria = "id_fe";

    public function getClaseEntidad() {
        return Forma_Envio::class;
    }
}