<?php

class Direccion {
    private ?string $tipo_via;
    private string $nombre_via;
    private int $numero;
    private int $portal;
    private string $escalera;
    private int $planta;
    private string $puerta;
    private int $cp;
    private string $localidad;

    private const array TIPOS_VIAS = ["C/", "Av", "Pz", "Ps", "Crta"];

    public function __construct(string $tv, string $nv, int $nu, int $po, string $es,
                                int $pl, string $pu, int $cp, string $lo) {

        $this->setTipoVia($tv);
        $this->nombre_via = $nv;
        $this->numero = $nu;
        $this->portal = $po;
        $this->escalera = $es;
        $this->planta = $pl;
        $this->puerta = $pu;
        $this->cp = $cp;
        $this->localidad = $lo;
    }

    // Sobrecarga de propiedades
    public function getTipoVia(): string {
        return $this->tipo_via;
    }

    public function setTipoVia(string $tv): void {
        if( in_array($tv, self::TIPOS_VIAS) ) 
            $this->tipo_via = $tv;
        else
            $this->tipo_via = null;
    }

    public function __get(string $propiedad): mixed {
        //                  __CLASS__
        if( property_exists(self::class, $propiedad)) {
            return $this->$propiedad;
        }
        else {
            echo "<p>Warning: La propiedad $propiedad sin definir en " . __CLASS__ . "</p>";
            return null;
        }
    }

    public function __set(string $propiedad, mixed $valor): void {
        if( property_exists(__CLASS__, $propiedad) ) {
            if( $propiedad == "tipo_via" ) {
                $this->setTipoVia($valor);
            }
            else {
                $this->$propiedad = $valor;
            }
        }
        else {
            echo "<p>Warning: La propiedad $propiedad sin definir en " . __CLASS__ . "</p>";
        }
    }

    public function __isset(string $propiedad): bool {
        if( property_exists(Direccion::class, $propiedad) ) {
            return isset($this->$propiedad);
        }
        else {
            return false;
        }
    }

    public function __unset(string $propiedad): void {
        if( property_exists(Direccion::class, $propiedad) ) {
            unset($this->$propiedad);
        }
        else {
            echo "Warning: La propiedad $propiedad sin definir en " . __CLASS__ . "</p>";
        }
    }

    public function __toString() :string {
        $cadena = self::class;
        $cadena.= ": {$this->tipo_via} {$this->nombre_via}, {$this->numero}<br>";
        $cadena.= "Portal: {$this->portal} Esc: {$this->escalera} Piso:{$this->planta}-{$this->puerta}<br>";
        $cadena.= "{$this->cp} {$this->localidad}";

        return $cadena;
    }

    // Sobrecarga de métodos
    public function __call(string $metodo, array $argumentos): mixed {
        
    }
}

?>