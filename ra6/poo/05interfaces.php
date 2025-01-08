<?php
require_once("GestionSeguridad.php");
require_once("Emp.php");
require_once("Cliente.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

function prueba_interface( GestionSeguridad $emp_cli, string $token) : bool {
    if( $emp_cli->autenticar($token) ) {
        echo "<p>Función: Autenticado con éxito</p>";
        return true;
    }
    else {
        echo "<p>Función: Error en autenticación</p>";
        return false;
    }

    
}
inicio_html("Interfaces en PHP", ["/estilos/general.css"]);

$emp1 = new Emp("30000001A", "manuel", "Manuel Gómez");



$emp1->nombre = "Manuel Sánchez";
$emp1->direccion = "c/ mayor, 2";

$cli1 = new Cliente("maria@gmail.com", "1234", "María González");

if( $emp1->autenticar("manuel") ) {
    echo "<p>$emp1 autenticado con éxito</p>";
}
else {
    echo "<p>$emp1 ERROR EN LA AUTENTICACIÓN</p>";
}

if( $cli1->autenticar("1234") ) {
    echo "<p>$cli1 autenticado con éxito</p>";
}
else {
    echo "<p>$cli1 ERROR EN LA AUTENTICACIÓN</p>";
}

prueba_interface($emp1, "manuel");
prueba_interface($cli1, "1234");


fin_html();
?>