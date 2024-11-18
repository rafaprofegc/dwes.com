<?php

class Chalet extends Casa {
    public static Chalet $instancia;

    public int $sup_piscina;    // Por piscina aumenta 50000 precio
    public int $sup_parcela;    // Son 500 € por m2 de parcela

    private function __construct(string $rc, string $di, int $s, int $sp, int $sj,
                                int $spi, int $spa) {
        parent::__construct($rc, $di, $s, $sp, $sj);
        $this->sup_piscina = $spi;
        $this->sup_parcela = $spa;
    }

    // Si un constructor es privado, no lo puedo usar para instanciar un objeto
    // de la clase. 

    // Tendría que definir un método estático que crea un objeto de la clase
    // usando el constructor privado y luego devuelve la instancia de ese objto.

    public static function getChalet(string $rc, string $di, int $s, int $sp, int $sj,
    int $spi, int $spa): self {
        if( self::$instancia === null ) {
            self::$instancia = new self($rc, $di, $s, $sp, $sj, $spi, $spa);
        }

        return self::$instancia;
    }

    /*
    public function getValorEstimado($precio_m): float {

    }
    */

}

?>