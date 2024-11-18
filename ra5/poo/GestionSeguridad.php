<?php
interface GestionSeguridad {
    public const LONGITUD_MINIMA_CLAVE = 6;
    public const LONGITUD_PIN = 4;

    public function autenticar($token): bool;
    public function cambiarToken($token_actual, $token_nuevo): bool;
}

?>