<?php
session_start();

if( !isset($_SESSION['cesta']) ) {
    header("Location: /ra4/sesiones/01sesion_inicio.php");
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/sesiones/01sesion_include.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

date_default_timezone_set("Europe/Madrid");

define("COSTE_KG", 10);

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {
    inicio_html("Sesiones en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);
    ver_datos_sesion();

    echo "<header>Mi cesta de Navidad</header>";
    echo "<table><thead><tr><th>Producto</th><th>Cantidad</th></tr></thead>";
    echo "<tbody>";
    foreach( $_SESSION['cesta'] as $producto ) {
        echo "<tr><td>{$producto[0]}</td><td>{$producto[1]}</td></tr>";
        $coste[] = $producto[1];
    }
    echo "</tbody></table>";
    echo "<h3>El coste de toda la cesta es " . number_format(array_sum($coste) * COSTE_KG) . " €</h3>";

    echo "<p><a href='/ra4/sesiones/01sesion_inicio.php?operacion=cerrar'>Cerrar la sesión y empezar otra vez</a></p>";
}