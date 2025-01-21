<?php
namespace orm\entidad;

use orm\entidad\Entidad;

class DireccionEnvio extends Entidad {
    protected string $nif;
    protected int $id_dir_env;
    protected string $direccion;
    protected string $cp;
    protected string $poblacion;
    protected string $provincia;
    protected string $pais;
}