<?php
namespace orm\bd;

class Database {

    private static $instance = null;

    private function __construct() {
        $dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
        $usuario = "rlozano";
        $clave = "usuario";
        $opciones = [];
    }
}

?>