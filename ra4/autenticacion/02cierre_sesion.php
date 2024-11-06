<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

if( !isset($_SESSION['usuario']) ) {
    header("Location: /ra4/autenticacion/02autenticacion_form.php");
}

// Aquí la sesión todavía está activa

// 1º Elimino la cookie con el ID de sesión
$parametros = session_get_cookie_params();
setcookie( session_name(), '', time() - 10000, $parametros['path'],
                $parametros['domain'], $parametros['secure'], $parametros['httponly']);

// 2º Borro las variables de $_SESSION
session_unset();

// 3º Borro la sesión
session_destroy();

inicio_html("Autenticacion con formulario", ["/estilos/general.css"]);
echo "<header>Autenticación de usuario</header>";
echo "<p>Id de sesión: " . session_id() . "<br>";
echo "<p>Login de usuario: " . (isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Sin definir") . "</p>";

echo "<p><a href='/ra4/autenticacion/02autenticacion_form.php'>Ir al inicio</a></p>";

fin_html();
//header("Location: /ra4/autenticacion/02autenticacion_form.php");
?>