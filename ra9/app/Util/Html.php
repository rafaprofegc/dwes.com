<?php
namespace App\Util;

class Html {
    public static function inicio(string $titulo, array $estilos) {
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?=$titulo?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
    foreach($estilos as $hoja) {
        echo "<link type='text/css' rel='stylesheet' href='". url($hoja) . "'>";
    }
?>
    </head>
<?php
    }

    public static function fin() {
        echo "</body>";
        echo "</html>";
    }

    public static function start(string $titulo, array $estilos) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
            <head>
                <title><?=$titulo?></title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
            foreach($estilos as $hoja) {
                echo "<link type='text/css' rel='stylesheet' href='". url($hoja) . "'>";
            }
        ?>
            </head>
        <?php
            }
        
            public static function end() {
                echo "</body>";
                echo "</html>";
            }
}

?>