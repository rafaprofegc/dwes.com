<?php
namespace ra6\utils;

class Direccion {
    public string $tipo_via;
    public string $nombre_via;
    public int $numero;

    public function __construct(string $tv, string $nv, int $n) {
        $this->tipo_via = $tv;
        $this->nombre_via = $nv;
        $this->numero = $n;
    }

    public function __toString(): string {
        return "$this->tipo_via $this->nombre_via, $this->numero";
    }
}

?>