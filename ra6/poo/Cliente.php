<?php
require_once("GestionSeguridad.php");

class Cliente implements GestionSeguridad {
    private string $email;
    private string $pin;
    private string $nombre;

    public function __construct(string $e, string $p, string $n) {
        $this->email = $e;
        $this->pin = $p;
        $this->nombre = $n;
    }

    public function __get($propiedad): mixed {
        if( property_exists(self::class, $propiedad) ) {
            if( $propiedad != "pin" ) {
                return $this->$propiedad;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function __set($propiedad, $valor): void {
        if( property_exists(self::class, $propiedad) ) {
            if( $propiedad != "pin")
                $this->$propiedad = $valor;
        }
    }

    public function __toString(): string {
        return "Cli: $this->email - $this->nombre";
    }

    public function autenticar($token): bool {
        echo "<p>Autenticando al cliente</p>";
        return $token == $this->pin;
    }

    public function cambiarToken($token_actual, $token_nuevo): bool {
        if( $this->autenticar($token_actual) ) {
            if( mb_strlen($token_nuevo) == GestionSeguridad::LONGITUD_PIN ) {
                $this->pin = $token_nuevo;
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

?>