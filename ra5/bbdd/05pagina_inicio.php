<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");

if( isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];

    $payload = verificar_token($jwt);

    if( !$payload ) {
        header("Location: 04autenticacion.php");
    }
}

// En este punto el payload tiene los datos del usuario
inicio_html("BBDD - Mysqli", ["/estilos/general.css"]);
echo "<h3>Zona de usuarios autenticados</h3>";
echo "<p>¡Hola, {$payload['nombre']}! Aquí tienes nuestro catálogo</p>";
echo "<p>Si quieres, puedes <a href='06modificar_cliente.php'>modificar tus datos</a> o ";
echo "<a href='07baja_cliente.php'>darte de baja</a></p>";
echo "<a href=''>Cambiar mi clave</a></p>";
echo "<a href=''>Cerrar la sesión</a></p>";
fin_html();

?>