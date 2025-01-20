<?php

namespace mvc\vista;

use mvc\vista\Vista;

class V_Error extends Vista {

    public function genera_salida(mixed $datos): void {
        ob_clean();
        
        $this->inicio_html("Error de la aplicación", ["/estilos/general.css"]);

        $archivo = $datos->getFile();   //var/www/dwes.com/ra5/mvc/M_Main.php
        $componentes = explode("/", $archivo); // ['var', 'www', 'dwes.com', 'ra5', 'mvc', 'M_Main.php']
        $script = end($componentes);    // M_Main.php
        $script = rtrim($script, ".php");

        echo <<<ERROR
        <h3>Error de la aplicación</h3>
        <p>Mensaje de error: {$datos->getMessage() } <br>
        Código de error: {$datos->getCode()} <br>
        Módulo: $script <br>
        Línea: {$datos->getLine()}
        </p>
        <p>Puede dirigirse <a href="/ra5/index.php">aquí</a></p>
        ERROR;
        $this->fin_html();
    }

}