<?php
session_start();

if( !isset($_SESSION['cesta']) ) {
    $_SESSION['cesta'] = [];
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("01sesion_include.php");

date_default_timezone_set("Europe/Madrid");

inicio_html("Sesiones en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] == "POST" 
        && htmlspecialchars($_POST['operacion']) == "Añadir a la cesta") {
    
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if( $email ) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['email'] = $email;
    }
    else {
        echo "<h3>Datos personales no válidos</h3>";
        echo "<p><a href='/ra4/sesiones/01sesion_inicio.php'>Emepezar otra vez</a></p>";
        exit(1);
    }
}

echo "<header>Mi cesta de Navidad</header>";
ver_datos_sesion();

if( isset($_SESSION['email']) ) {
?>
<form action="/ra4/sesiones/01sesion_cesta.php" method="POST">
    <fieldset>
        <legend>Añadimos un producto a la cesta</legend>
        <label for="dulce">Dulce de Navidad</label>
        <input type="text" name="dulce" id="dulce" size="30" required>

        <label for="cantidad">Cantidad</label>
        <input type="number" name="cantidad" id="cantidad" size="2" required>

    </fieldset>
    <input type="submit" name="operacion" value="Mete en la cesta">
</form>

<?php
}
else {
    echo "<h3>No se ha establecido el los datos personales</h3>";
    echo "<p><a href='/ra4/sesiones/01sesion_inicio.php'>Iniciar la compra</a></p>";
}
fin_html();
?>