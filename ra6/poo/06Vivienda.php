<?php
/*
Nivel de acceso:

Las propiedades y métodos de una clase se puden declarar:
    - private -> Solo es accesible desde dentro de clase. 

    - protected -> Solo es accesible desde dentro de la clase y clases derivadas.

    - public -> Es accesible desde cualquier parte.

*/

abstract class Vivienda {
    public string $ref_cat;
    public string $direccion;
    public int $superficie;

    public function __construct(string $rc, string $d, int $s) {
        $this->ref_cat = $rc;
        $this->direccion = $d;
        $this->superficie = $s;
    }
    
    /*
    public function getValorEstimado(float $precio_m): float {
        return $this->superficie * $precio_m;
    }
    */
    public abstract function getValorEstimado(float $precio_m): float;

    public function __toString() {
        return "$this->ref_cat $this->direccion";
    }
}
?>