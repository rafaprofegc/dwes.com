<?php
namespace orm\entidad;

use DateTime;
use orm\entidad\Entidad;

class Pedido extends Entidad {
    protected ?int $npedido;
    protected string $nif;
    protected DateTime $fecha;
    protected ?string $observaciones;
    protected ?string $total_pedido;
}
?>