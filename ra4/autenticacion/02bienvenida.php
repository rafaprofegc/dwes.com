<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Autenticación con formulario", ["/estilos/general.css"]);

if( isset($_SESSION['usuario'], $_SESSION['nombre'], $_SESSION['perfil'])) {
    echo "<h3>Bienvenido {$_SESSION['nombre']}</h3>";
    echo "<p>Su login es {$_SESSION['usuario']} y su perfil es {$_SESSION['perfil']}</p>";
    echo "<p>Si lo desea puede cerrar la sesión <a href='/ra4/autenticacion/02cierre_sesion.php'>aquí</a></p>";
}
else {
    echo "<h3>Vd. no se ha autenticado todavía</h3>";
    echo "<p><a href='/ra4/autenticacion/02autenticacion_form.php'>Ir a la autenticación</a></p>";
}

fin_html();
?>