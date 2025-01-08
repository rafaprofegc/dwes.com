<?php
require_once("06Vivienda.php");

final class Piso extends Vivienda {
    public int $planta;
    public string $puerta;
    
    public function __construct(string $rc, string $di, int $s, int $pl, string $pu) {
        parent::__construct($rc, $di, $s);
        $this->planta = $pl;
        $this->puerta = $pu;
    }

    public function __toString(): string {
        $piso = parent::__toString();
        $piso.= " - $this->planta $this->puerta"; 
        return $piso;
    }

    public function getValorEstimado(float $precio_m): float {
        // $valor_estimado = parent::getValorEstimado($precio_m); Ahora es abstracto
        $valor_estimado = $this->superficie * $precio_m;
        $valor_estimado += $this->planta * 0.1 * $valor_estimado;

        return $valor_estimado;
    }

    public function getRefCat() {
        return $this->ref_cat;
    }

    
}

?>