<?php
namespace ra6\poo;

use ra6\utils\Direccion;

class Empleado {
    public string $nif;
    public string $nombre;
    public Direccion $dir;


    public function __construct(string $n, string $no) {
        $this->nif = $n;
        $this->nombre = $no;
    }

    public function __toString(): string {
        return "$this->nif $this->nombre";
    }
}

?>