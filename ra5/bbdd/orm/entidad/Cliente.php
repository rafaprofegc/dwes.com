<?php
namespace orm\entidad;

class Cliente extends Entidad {
    protected string $nif;
    protected string $nombre;
    protected string $apellidos;
    protected string $clave;
    protected string $iban;
    protected ?string $telefono;
    protected string $email;
    protected ?float $ventas;
}
?>