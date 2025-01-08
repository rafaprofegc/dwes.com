<?php

class Casa extends Vivienda {
    public int $sup_patio;
    public int $sup_jardin;

    public static int $METRO_JARDIN = 30;
    public static int $METRO_PATIO = 25;
    
    public function __construct(string $rc, string $di, int $s, int $sp, int $sj) {
        parent::__construct($rc, $di, $s);
        $this->sup_patio = $sp;
        $this->sup_jardin = $sj;
    }

    final public function getValorEstimado($precio_m): float {
        //$valor_estimado = parent::getValorEstimado($precio_m); Ahora es abstracto
        $valor_estimado = $this->superficie * $precio_m;
        $valor_estimado += $this->sup_patio * self::$METRO_PATIO;
        $valor_estimado += $this->sup_jardin * self::$METRO_JARDIN;

        return $valor_estimado;
    }

    public function __toString(): string {
        $casa = parent::__toString();
        $casa .= " - $this->sup_patio m<sup>2</sup> $this->sup_jardin m<sup>2</sup>";
        return $casa;
    }

}

?>