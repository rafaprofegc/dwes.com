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
        exit(1);
    }

    // El token es válido
    if( !isset($_SESSION['ingredientes']) || !isset($_SESSION['vegetariana']) ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(2);
    }
    
    inicio_html("Pizzas por encargo", ["/estilos/general.css","/estilos/formulario.css", "/estilos/tablas.css"]);
    echo "<header>Pizzas por encargo</header>";

    MuestraDatos($payload, $_SESSION['ingredientes'], $_SESSION['vegetariana'] )

?>
    <form method="POST" action="/ra4/actividad/act03_script04.php">
        <fieldset>
            <legend>Extras de la pizza</legend>
            <label for="queso">Extra de queso</label>
            <input type="checkbox" name="queso" id="queso">

            <label for="bordes">Bordes rellenos</label>
            <input type="checkbox" name="bordes" id="bordes">
        </fieldset>
        <input type="submit" value="Terminar la pizza">
    </form>
<?php
fin_html();
}
else {
    header("Location: /ra4/actividad/act03_script01.php");
}
?>