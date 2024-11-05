<?php
function ver_datos_sesion() {
    echo "<p>";
    echo "Id de sesión: " . session_id() . "<br>";
    if( isset($_SESSION['instante']) ) {
        echo "Momento de creación: " . date("D, d F Y G:i:s", $_SESSION['instante']) . "<br>";
    }
    else {
        echo "Momento de creación: no está definido<br>";
    }
    echo "Nombre: " . (isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "El nombre todavía no se ha definido");
    echo "<br>"; 
    echo "Email: " . (isset($_SESSION['email']) ? $_SESSION['email'] : "El email todavía no se ha definido"); 
    echo "<br>";
    echo "Productos en la cesta: " . (isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : "0");
    echo "</p>";
}

function cerrar_sesion() {
    
    // 1º Destruir el id de sesión
    $nombre_id = session_name();
    $parametros_cookie = session_get_cookie_params();
    setcookie($nombre_id, '', time() - 10000,
        $parametros_cookie['path'], $parametros_cookie['domain'],
        $parametros_cookie['secure'], $parametros_cookie['httponly'] );

    // 2º Destruir las variables de sesión
    session_unset();

    // 3º Destruir los datos de la sesión
    session_destroy();
}
?>