<?php
namespace orm\entidad;

use orm\entidad\Entidad;

class Forma_Envio extends Entidad {
    protected string $id_fe;
    protected string $descripcion;
    protected string $telefono;
    protected ?string $contacto;
    protected ?string $email;
    protected float $coste;

}