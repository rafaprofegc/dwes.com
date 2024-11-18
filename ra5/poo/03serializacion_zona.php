<?php
require_once("Usuario.php");

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");


inicio_html("Serialización de objetos en PHP", ["/estilos/general.css"]);

if( isset($_SESSION['usuario']) ) {
    $objeto_usuario = $_SESSION['usuario'];

    echo "<h3>Zona de usuarios con perfil {$objeto_usuario->perfil}</h3>";
    echo "<p>Usuario: {$objeto_usuario->login}<br>";
    echo "<p>Nombre: {$objeto_usuario->nombre}<br>";
    echo "<p>Perfil: {$objeto_usuario->perfil}</p>";

    $objeto_usuario->registraActividad("Acceso a su zona restringida");
}

fin_html();
?>