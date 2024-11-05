<?php
session_start();

if( !isset($_SESSION['nombre']) || !isset($_SESSION['email']) ) {
    header("Location: /ra4/sesiones/01sesion_inicio.php");
}

if( !isset($_SESSION['cesta'])) {
    $_SESSION['cesta'] = [];
}

date_default_timezone_set("Europe/Madrid");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("01sesion_include.php");

inicio_html("Sesiones en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);

echo "<header>Mi cesta de Navidad</header>";

if( $_SERVER['REQUEST_METHOD'] == "POST" 
        && htmlspecialchars($_POST['operacion']) == "Mete en la cesta") {
    $dulce = filter_input(INPUT_POST, 'dulce', FILTER_SANITIZE_SPECIAL_CHARS);
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_SANITIZE_NUMBER_FLOAT);
    $cantidad = filter_var($cantidad, FILTER_VALIDATE_FLOAT, 
            Array('options' => ['min_range' => 1, 'default' => 1],
                  'flags' => FILTER_FLAG_ALLOW_FRACTION));

    $_SESSION['cesta'][] = Array($dulce, $cantidad); 

    echo "<p>¡¡Estupendo!! Ya hemos añadido el producto a tu cesta</p>";
    echo "<p>Tu cesta queda de la siguiente forma</p>";

    echo "<table><thead><tr><th>Producto</th><th>Cantidad</th></tr></thead>";
    echo "<tbody>";
    foreach( $_SESSION['cesta'] as $producto ) {
        echo "<tr><td>{$producto[0]}</td><td>{$producto[1]}</td></tr>";
    }
    echo "</tbody></table>";

    echo "<p>Si lo desea, puede <a href='/ra4/sesiones/01sesion_datos.php'>añadir otro producto a su cesta</a></p>";
    echo "<p>O si lo prefiere, puede <a href='/ra4/sesiones/01sesion_fin.php'>finalizar su cesta</a></p>";
}

ver_datos_sesion();

fin_html();
?>