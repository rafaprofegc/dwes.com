<?php
namespace orm\entidad;

use DateTime;

class Articulo extends Entidad {

    public const IVA_REDUCIDO = 'R';
    public const IVA_NORMAL = 'N';
    public const IVA_SUPERREDUCIDO = 'SR';

    public static array $TIPOS_IVA = ['R' => "Reducido", "N" => "Normal", "SR" => "Superreducido"];
    
    protected string $referencia;
    protected string $descripcion;
    protected float $pvp;
    protected ?float $dto_venta;
    protected ?float $und_vendidas;
    protected ?float $und_disponibles;
    protected ?DateTime $fecha_disponible;
    protected string $categoria;
    protected ?string $tipo_iva;

}