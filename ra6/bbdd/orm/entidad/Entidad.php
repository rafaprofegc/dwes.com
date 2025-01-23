<?php
namespace orm\entidad;

use DateTime;
use DateTimeZone;
use Exception;
use JsonSerializable;
use ReflectionProperty;

abstract class Entidad implements JsonSerializable {

    public const FECHA_HORA_MYSQL = "Y-m-d H:i:s";
    public const FECHA_HORA_USUARIO = "d/m/Y H:i:s";
    public const FECHA_HORA_ORACLE = "d-m-y H:i:s";

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
            // Compruebo si la propiedad es de tipo DateTime
            $nombre_tipo = $this->tipoPropiedad($this, $propiedad);
            if( $nombre_tipo == DateTime::class && $valor ) {
                /* Creo un objeto DateTime con $valor
                Formato de fecha en MySQL: yyyy-mm-dd
                Formato de fecha y hora en MySQL: yyyy-mm-dd HH:MM:SS
                Ambos formatos son válidos para el constructor de DateTime
                */
                if( $valor instanceof DateTime ) {
                    $this->$propiedad = $valor;
                }
                else {
                    $this->$propiedad = new DateTime($valor, new DateTimeZone("Europe/Madrid"));
                }
            }
            else {
                // La propiedad no es DateTime.
                // Se asigna el valor directamente.
                $this->$propiedad = $valor;
            }
            
        }
        else {
            throw new Exception("La propiedad no existe", 1);
        }
    
    }

    public function toArray(): array {
        return get_object_vars($this);
    }
    
    public function __toString(): string {
        $cadena = "<h4>Clase: " . self::class . "</h4>";
        $cadena.= "<p>";
        foreach( $this as $propiedad => $valor ) {
            $tipo_propiedad = $this->tipoPropiedad($this, $propiedad);
            if( $tipo_propiedad == DateTime::class ) {
                $cadena.= "$propiedad = " . $valor->format(self::FECHA_HORA_USUARIO) . "<br>";
            }
            else {
                $cadena.= "$propiedad = $valor<br>";
            }
            
        }
        $cadena.= "</p>";
        return $cadena;
    }

    public static function tipoPropiedad($objeto, $propiedad) {
        $info_propiedad = new ReflectionProperty($objeto, $propiedad);
        $tipo_propiedad = $info_propiedad->getType();
        $nombre_tipo = $tipo_propiedad->getName();
        return $nombre_tipo;
    }

    public function __serialize(): array {
        return $this->toArray();
    }

    public function __sleep(): array {
        // Accedemos a las propiedades
        foreach( $this as $propiedad => $valor ) {
            $propiedades[] = $propiedad;
        }

        return $propiedades;
    }

    public function jsonSerialize(): mixed {
        $objeto_json = [];
        
        foreach($this as $propiedad => $valor ) {
            $reflexion_propiedad = new ReflectionProperty($this, $propiedad);
            $tipo_datos = $reflexion_propiedad->getType()->getName();
            if ( $tipo_datos === DateTime::class && $valor ) {
                $objeto_json[$propiedad] = $valor->format(self::FECHA_HORA_USUARIO);
            }
            else {
                $objeto_json[$propiedad] = $valor;
            }          
        }
        return  $objeto_json;
    }
           
}

?>