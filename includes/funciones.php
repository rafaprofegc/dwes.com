<?php
function inicio_html($titulo, $estilos) { ?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width;initial-scale=1'>
<?php
        foreach( $estilos as $estilo) {
            echo "\t\t<link rel='stylesheet' type='text/css' href='$estilo'>\n";
        }
?>
        
        <title><?=$titulo?></title>
    </head>
    <body>
<?php
}

function fin_html() {?>
        </body>
    </html> 
<?php
}

function registrar_carga_clases(): void {
    try  {
        spl_autoload_register("autocarga_clases");
    }
    catch( Exception $e) {
        echo "<h3>Error en la carga de una clase</h3>";
        exit(1);
    }
}

define("DIRECTORIO_CLASES", __DIR__);

function autocarga_clases(string $clase): void {
    /*
    El argumento $clase tiene el nombre de la clase
    que PHP está instanciando. Por tanto, necesita el archivo
    PHP que contiene su definición.

    Si la clase tiena asignado espacio de nombres, el nombre
    de la clase contiene su espacio de nombres. Ej

    $emp1 = new Empleado(...);      La clase es ra6\poo\Empleado

    Si la clase no tiene espacio de nombres, es simplemente su nombre
    
    $objeto = new ClaseSinEN(...);  La clase es ClaseSinEN

    */
    $clase = str_replace("\\", "/", $clase);
    if( file_exists(DIRECTORIO_CLASES . "/$clase.php")) {
        require_once(DIRECTORIO_CLASES. "/$clase.php");
    }
    else {
        throw new Exception("El archivo de la clase $clase NO existe");
    }   
}

function mostrar_error(Exception $e) {
    echo "<h3>Error de la aplicación</h3>";
    echo "<p>Código de error: " . $e->getCode() . "<br>";
    echo "Mensaje de error: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "</p>";

    if( property_exists($e, "punto_recuperacion") ) {
        echo "<p>Puede ir a <a href='{$e->punto_recuperacion['url']}'>{$e->punto_recuperacion['enlace']}</a</p>";
    }
    fin_html();
    exit($e->getCode());
}

function cerrar_sesion() {
    $parametros = session_get_cookie_params();
    $nombre_sesion = session_name();
    setcookie($nombre_sesion, "", time() - 1000, $parametros['path'], $parametros['domain'],
                                                 $parametros['secure'], $parametros['httponly']);
    
    setcookie("jwt", "", time() - 1000);
    
    session_unset();

    session_destroy();
}
?>