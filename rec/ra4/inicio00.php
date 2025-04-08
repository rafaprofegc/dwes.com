<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once("include00.php");

$usuarios = ['30A' => ['clave' => password_hash("usu123", PASSWORD_DEFAULT), 'nombre' => 'Juan'],
             '30B' => ['clave' => password_hash("usa456", PASSWORD_DEFAULT), 'nombre' => 'María']
];

function autenticacion() {
    global $usuarios;

    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);
    $clave = filter_input(INPUT_POST, 'clave', FILTER_DEFAULT);

    if( array_key_exists($dni, $usuarios) ) {
        if( password_verify($clave, $usuarios[$dni]['clave']) ) {
            // Usuario autenticado
            unset($_SESSION['error']);
            return ['dni' => $dni, 'nombre' => $usuarios[$dni]['nombre']];
        }
        else {
            $_SESSION['error'] = "La clave no es correcta";
        }
    }
    else {
        $_SESSION['error'] = "El usuario no existe";
    }

    return false;


}

// Autenticar el usuario
$payload = autenticacion();

if( $payload ) {
    // Si éxito. Genero JWT y presento datos con enlace

    // Si la función autenticacion() devuelve solo el dni
    //$payload = ['dni' =>  $dni, 'nombre' => $usuarios[$dni]['nombre']];

    // Genero el JWT
    $jwt = generar_token($payload);
    setcookie("jwt", $jwt, time() + 60 * 60, "/", "dwes.com", false, true );

    // Establecemos variables de sesión
    $_SESSION['hora'] = time();
    $_SESSION['cursos'] = [];

    // Presentamos los datos
    inicio_html("Recuperación RA4 - Autenticación", 
            ["/estilos/general.css"]);
    presentar_datos($payload);
    echo "<a href='lista00.php'>Ir a la selección de cursos</a>";
    fin_html();
}
else {
    // Si no éxito. Redirecciono con mensaje de error
    header("Location: index00.php");
}
?>