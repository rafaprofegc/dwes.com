<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once("act03_include.php");

if( $_SERVER['REQUEST_METHOD'] == "POST") {
    if( !isset($_COOKIE['token']) ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(3);
    }
    $jwt = $_COOKIE['token'];
    
    $payload = verificar_token($jwt);
    if( !$payload ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(3);
    }

    // Tenemos el token
    if( !isset($_SESSION['ingredientes']) || !isset($_SESSION['vegetariana']) ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(2);
    }

    /* Punto e)
        Las pizzas suelen incluir ofertas de forma frecuente, así que la duración 
        de la página final en la caché será de solo 2 días.
    */
    
    $caducidad = gmdate("D, d M Y G:i:s", time() + 60 * 60 * 3) . " GMT";
    header("Expires: {$caducidad}");


    $extra_queso = filter_input(INPUT_POST, 'queso', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    $bordes_rellenos = filter_input(INPUT_POST, 'bordes', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

    inicio_html("Actividad 03 Mantenimiento de estado", ["/estilos/general.css", "/estilos/formulario.css"]);
    echo "<header>Pizzas por encargo</header>";

    MuestraDatos($payload, $_SESSION['ingredientes'], $_SESSION['vegetariana'], 
                    $extra_queso, $bordes_rellenos);
?>
    <form action="/ra4/actividad/act03_script01.php" method="POST">
        <input type="submit" name="operacion" id="operacion" value="Empezar de nuevo">
    </form>
<?php
fin_html();
}
else {
    header("Location: /ra4/actividad/act03_script01.php");
}
?>