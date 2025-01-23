<?php
namespace util;

use Exception;

class Autocarga {

    public static function registra_autocarga() {
        try {
            spl_autoload_register(self::class . "::autocarga");
        }
        catch(Exception $e) {
            echo "La definición de la clase no se ha encontrado";
            exit(1);
        }
    }

    public static function autocarga($clase): void {
        $directorios = ['/ra5', '/ra6/bbdd', '/ra7'];

        $clase = str_replace("\\", "/", $clase);
        $encontrado = False;

        foreach($directorios as $directorio ) {
            if( file_exists("{$_SERVER['DOCUMENT_ROOT']}$directorio/$clase.php") ) {
                require_once("{$_SERVER['DOCUMENT_ROOT']}$directorio/$clase.php");
                $encontrado = True;
                break;
            }
        }
        if( !$encontrado ) throw new Exception("La clase $clase no existe");
    }
}
?>