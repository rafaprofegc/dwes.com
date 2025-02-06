<?php

namespace util;

class Autocarga {
    public static function registraAutocarga() {
        if( !spl_autoload_register([self::class, "autocarga"]) ) {
            echo "Error en la autocarga";
        }
    }

    public static function autocarga(string $clase): void {
        $nombre_clase = str_replace("\\", "/",$clase);
        $carpetas = ['/ra7', '/ra6/bbdd'];

        foreach( $carpetas as $carpeta ) {
            $encontrado = False;
            $ruta_completa = $_SERVER['DOCUMENT_ROOT'] . "{$carpeta}/{$nombre_clase}.php";

            if( file_exists($ruta_completa) ) {
                $encontrado = True;
                require_once($ruta_completa);
                break;
            }
        }
        if( !$encontrado ) {
            echo "No existe el archivo $ruta_completa.php";
        }

    }
}