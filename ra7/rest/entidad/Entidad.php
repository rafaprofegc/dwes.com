<?php
namespace rest\entidad;

use ReflectionProperty;
use DateTime;
use JsonSerializable;

abstract class Entidad implements JsonSerializable {

    public const FORMATO_FECHA = "Y-m-d H:i:s";

    public function __construct(array $datos) {
        foreach($datos as $propiedad => $valor ) {
            $this->__set($propiedad, $valor);
        }
    }

    public function __get(string $propiedad) : mixed {
        if( property_exists($this, $propiedad) ) {
            return $this->$propiedad;
        }
        else {
            return null;
        }
    }

    public function __set(string $propiedad, mixed $valor): void {
        if( property_exists($this, $propiedad) ) {
            $reflexion = new ReflectionProperty($this, $propiedad);
            $tipo_propiedad = $reflexion->getType();
            $nombre_tipo = $tipo_propiedad->getName();
            if( $nombre_tipo == DateTime::class ) {
                if( $valor instanceof DateTime ) {
                    $this->$propiedad = $valor;
                }
                elseif( gettype($valor) == "string") {
                    $this->$propiedad = new DateTime($valor);
                }
            }
            else {
                $this->$propiedad = $valor;
            }
        }
    }

    public function toArray(): array {
        $propiedades = get_object_vars($this);
        foreach($propiedades as $propiedad => $valor ) {
            if( $valor instanceof \DateTime ) {
                $propiedades[$propiedad] = $valor->format(self::FORMATO_FECHA);
            }
        }
        return $propiedades;
    }

    public function jsonSerialize(): array {
        $objeto_json = [];
        foreach( $this as $propiedad => $valor ) {
            if( $this->getTipo($propiedad) == DateTime::class ) {
                $objeto_json[$propiedad] = $valor->format(self::FORMATO_FECHA);
            }
            else {
                $objeto_json[$propiedad] = $valor;
            }
        }
        return $objeto_json;
    }

    private function getTipo($propiedad): string {
        $reflexion = new ReflectionProperty($this, $propiedad);
        $tipo = $reflexion->getType()->getName();

        return $tipo;
    }
}