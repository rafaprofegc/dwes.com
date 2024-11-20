<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

registrar_carga_clases();

use ra6\bbdd\Usuario;

use ra6\poo\Empleado;
use ra6\utils\Direccion;

inicio_html("Espacio de nombres", ["/estilos/general.css"]);

$usu1 = new Usuario("pepe", "José García", "Adm");

$emp1 = new Empleado("30000001A", "Manuel González");

echo "$usu1<br>";
echo "$emp1<br>";

fin_html();

?>