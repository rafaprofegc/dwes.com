<?php

namespace util;

class HTML {
    public static function inicio_html(string $titulo, array $estilos, array $scripts) {
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?=$titulo?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
        foreach($estilos as $estilo) {
            echo "\t\t<link href='$estilo' type='text/css' rel='stylesheet'>\n";
        }
        foreach($scripts as $script) {
            echo "\t\t<script type='text/javascript' src='$script'></script>\n";
        }
?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", registra_eventos);
        </script>
    </head>
    <body>
<?php
    }

    public static function fin_html() {
        echo "</body>";
        echo "</html>";
    }
}