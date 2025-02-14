<?php
namespace rest\entidad;

use rest\entidad\Entidad;
use DateTime;

class Articulo extends Entidad {
    protected ?string $referencia;
    protected ?string $descripcion;
    protected ?float $pvp;
    protected ?float $dto_venta;
    protected ?float $und_vendidas;
    protected ?float $und_disponibles;
    protected ?DateTime $fecha_disponible;
    protected ?string $categoria;
    protected ?string $tipo_iva;

}