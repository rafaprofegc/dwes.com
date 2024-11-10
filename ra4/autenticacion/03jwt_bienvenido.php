<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("03jwt_include.php");

inicio_html("Autenticación con JWT", ["/estilos/general.css"]);
echo "<header>Autenticación con JWT</header>";

// 1º Obtener el token

$jwt = $_COOKIE['jwt'];

// 2º Verificar el token

$payload = verificar_token($jwt);


// 3º Si el token es válido, se presenta la página
if( $payload ) {
    echo "<p>Bienvenido a la zona restringida<br>";
    echo "Id de usuario {$payload['id']}<br>";
    echo "Nombre de usuario: {$payload['username']}<br>";
    echo "Perfil de usuario: {$payload['role']}</p>";
}
else {
    // 4ª Si el token no es válido, se emite mensaje error (o redirección al inicio)
    echo "<p>Su identificación no es válida</p>";
    echo "<p>Puede <a href='/ra4/autenticacion/03jwt_autenticacion.php'>volver a autenticarse</a> en la pantalla de inicio</p>";
}
fin_html();
?>