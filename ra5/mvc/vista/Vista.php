<?php
namespace mvc\vista;

abstract class Vista {
    public const FORMATO_FECHA = "d/m/Y";

    abstract public function genera_salida(mixed $datos): void;

    protected function inicio_html(string $titulo, array $estilos ) {
        ob_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial_scale=1.0">
        <title><?=$titulo?></title>
<?php
        foreach($estilos as $hoja) {
            echo "<link type='text/css' rel='stylesheet' href='$hoja'>";
        }
?>
    </head>
    <body>
        <h2>Tienda Online</h2>
<?php
    }

    protected function fin_html() {
        echo "</body>";
        echo "</html>";
        ob_end_flush();
    }
}
?>