<?php
/*
    Autenticación HTTP
    ------------------

    Consiste en enviar las siguiente cabeceras:
    
    header("WWW-Authentication: Basic realm='Acceso restringido');
    header("HTTP/1.1 401 Unauthorized");

    En la primera petición, cuando el navegador recibe estas cabeceras
    muestra un cuadro de autenticación para que el usuario introduzca
    usuario y clave.

    Las credenciales se envían al servidor, al mismo script, y sus valores
    se guardan en:

        $_SERVER['PHP_AUTH_USER']
        $_SERVER['PHP_AUTH_PW']

    Se leen estos datos, se comprueba con la BD de usuarios y si no son válidas
    se envía un 401 Unauthorized 

    Para guardar las credenciales de usuario se suele emplear una tabla de BD. En
    este ejemplo utilizamos un array bidimensional con login de usuario como clave
    y cada elemento es nombre y password hasheada (resumida)

    Algoritmos de hash
    ------------------
    Los más habituales son: SHA1, SHA2, SHA256 y SHA512

    Funciones PHP para obtener hash de una cadena:
    sha1(string clave) -> Devuelve un hash de 160 bits o 40Hx
    */

session_start();

define("INTENTOS_MAX", 3);

$usuarios = ['manuel01' => ['nombre' => "Manuel García", 'clave' => hash("sha512", "abc123")],
             'maria02'  => ['nombre' => "María López", 'clave' => hash("sha512", "123abc")]
];

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Autenticación HTTP", ["/estilos/general.css"]);

/*
foreach( $usuarios as $usuario ) {
    echo "{$usuario['nombre']} -{$usuario['clave']}<br>";
}
*/

if (isset($_SESSION['intentos']) && $_SESSION['intentos'] >= INTENTOS_MAX) {
    echo "<h3>Has superado el número maximo de intentos</h3>";
    fin_html();
    exit;
}

$authOk = False;
if( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) ) {
    $usuario = htmlspecialchars($_SERVER['PHP_AUTH_USER']);
    $password = $_SERVER['PHP_AUTH_PW'];

    if( array_key_exists($usuario, $usuarios) ) {
        $password_hasheada = hash("sha512", $password);

        if( $password_hasheada == $usuarios[$usuario]['clave']) {
            $authOk = True;
        }
    }
}

if( !$authOk ) {

    if( isset($_SESSION['intentos']) ){
        $_SESSION['intentos'] = $_SESSION['intentos'] + 1;
    }
    else {
        $_SESSION['intentos'] =  1;
    }

    header("WWW-Authenticate: Basic realm='Zona restringida'");
    header("HTTP/1.1 401 Unauthorized");

    echo "<h3>Vd no está autorizado. Se necesita autenticación para acceder</h3>";
    echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver a intentarlo</a>";
}

// Aquí va el contenido que se visualiza si la autenticación ha tenido éxito.
echo "<h1>Zona restringida</h1>";
echo "<h3>Bienvenido {$usuarios[$usuario]['nombre']} a la zona restringida</h3>";

fin_html();
?>