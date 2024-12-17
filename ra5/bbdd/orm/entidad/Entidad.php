<?php
namespace orm\entidad;

abstract class Entidad {

    public function __construct( array $datos ) {
        foreach($datos as $propiedad => $valor) {
            $this->__set($propiedad, $valor);
        }
    }

    public function __get($propiedad): mixed {
        if( property_exists($this, $propiedad) ) {
            return $this->$propiedad;
        }
        return null;
    }

    public function __set($propiedad, $valor): void {
        if( property_exists($this, $propiedad) ) {
            $this->$propiedad = $valor;
        }
    }

    public function toArray(): array {
        return get_object_vars($this);
    }
    
    public function __toString(): string {
        $cadena = "<h4>Clase: " . self::class . "</h4>";
        $cadena.= "<p>";
        foreach( $this as $propiedad => $valor ) {
            $cadena.= "$propiedad = $valor<br>";
        }
        $cadena.= "</p>";
        return $cadena;
    }
}

?>