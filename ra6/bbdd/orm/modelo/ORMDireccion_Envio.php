<?php
namespace orm\modelo;

use orm\modelo\ORMBase;
use orm\entidad\DireccionEnvio;

class ORMDireccion_Envio extends ORMBase {
    protected string $tabla = "direccion_envio";
    protected string $clave_primaria = "id_dir_env";

    public function getClaseEntidad() {
        return DireccionEnvio::class;
    }
}
?>