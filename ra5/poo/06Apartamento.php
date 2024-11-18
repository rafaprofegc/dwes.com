<?php

class Apartamento extends Piso {
    public int $habitaciones;

    public function __construct(string $rc, string $di, int $s, int $pl, string $pu, int $h) {
        parent::__construct($rc, $di, $s, $pl, $pu);
        $this->habitaciones = $h;
    }

    public function __toString(): string {
        $apart = parent::__toString();
        $apart.= " - $this->habitaciones H";
        return $apart;
    }
}