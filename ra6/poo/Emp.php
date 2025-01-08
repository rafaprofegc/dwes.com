<?php

require_once("GestionSeguridad.php");

class Emp implements GestionSeguridad {

    private string $nif;
    private string $clave;
    private string $nombre;

    public function __construct(string $ni, string $cl, string $no) {
        $this->nif = $ni;
        $this->clave = password_hash($cl, PASSWORD_DEFAULT);
        $this->nombre = $no;
    }

    public function __get($propiedad): mixed {
        if( property_exists(self::class, $propiedad) ) {
            if( $propiedad != "clave" ) 
                return $this->$propiedad;
            else
                return null;
        }
        return null;
    }

    public function __set($propiedad, $valor): void {
        if( property_exists(self::class, $propiedad) ) {
            if( $propiedad != "clave")
                $this->$propiedad = $valor;
        }
    }

    public function __toString(): string {
        return "Emp: $this->nif - $this->nombre";
    }

    public function autenticar($token): bool {
        echo "<p>Autenticando al empleado</p>";
        return password_verify($token, $this->clave);
    }

    public function cambiarToken($token_actual, $token_nuevo): bool {
        if( $this->autenticar($token_actual) ) {
            // 1ª Comprobación. La nueva clave tiene 4 tipos de caracteres
            $letra_min = preg_match("/[a-z]/", $token_nuevo);
            $letra_may = preg_match("/[A-Z]/", $token_nuevo);
            $numero = preg_match("/[0-9]/", $token_nuevo);
            $caracteres = preg_match("/[#@$%&]", $token_nuevo);

            if( !$letra_min || !$letra_may || !$numero || !$caracteres ) {
                return false;
            }

            if( mb_strlen($token_nuevo) >= GestionSeguridad::LONGITUD_MINIMA_CLAVE ) {
                $this->clave = password_hash($token_nuevo, PASSWORD_DEFAULT);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

}