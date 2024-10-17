<?php
/*
  Para incluir archivos hay 4 funciones que funcionan casi igual:
  - include() -> Incluye el contenido del argumento en el lugar donde se invoque. Si
    el archivo no existe, se emite un WARNING y el script continua 

  - require() -> Incluye el contenido del argumento en lugar donde se invoca. Si
    el archivo no existe, se genera un error fatal y termina la ejecución del script.

  ¿Qué ocurre si incluyo el mismo archivo más de una vez? Error por duplicidad de definición
  de funciones. Para evitarlo:

  - include_once() -> Igual que include() pero si el archivo ya había sido previamente
    incluido, no lo incluye.
  - require_once() -> Igual que require() pero si el archivo ya había sido previamente
    incluido, no lo incluye.


*/

// Incluir archivos modificando el include_path de php.init
$include_path_actual = ini_get("include_path"); // Leo el valor actual de include_path
$include_path_actual .= (":" . $_SERVER['DOCUMENT_ROOT'] . "/includes"); //Añado el directorio
ini_set("include_path", $include_path_actual); // Asigno el nuevo include_path
//require("funciones.php");   // Solo tengo que poner el nombre del archivo


// Incluir archivos sin modificar el include_path y poniendo la ruta absoluta
require($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

// A partir de aquí utilizo las funciones de los archivos incluidos.
inicio_html("Inclusión de archivos", ["/estilos/general.css"]);

echo "<h1>Inclusión de archivos php en scripts PHP</h1>";

//fin_html();
?>