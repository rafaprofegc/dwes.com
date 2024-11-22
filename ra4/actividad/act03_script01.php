<?php
session_start();
/*
    Actividad 3 - Relación RA4
*/
if( isset($_POST['operacion'])) {
    $operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
}
else {
    $operacion = "";
}

if( $_SERVER['REQUEST_METHOD'] == "POST" && $operacion == 'Empezar de nuevo' ) {
    // 1º Borrar la cookie
    $nombre_sesion = session_name();
    $cookie_params = session_get_cookie_params();
    setcookie($nombre_sesion, "", time() - 100, 
            $cookie_params['path'], $cookie_params['domain'],
            $cookie_params['secure'], $cookie_params['httponly']);

    // 2º Borrar $_SESSION
    session_unset();

    // 3º Destruir la sesión
    session_destroy();

    // 4º Iniciar una nueva sesión
    session_start();  
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Pizzas por Internet", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Pizzas por Internet</header>";
echo "<h2>Bienvenido a Pizza Online</h2>";
echo "<p>Todas nuestras pizzas incluyen tomate frito y queso</p>";
echo "<p>El precio de inicio es de 5€</p>";
?>
<form action="act03_script02.php?datos=1" method="POST">
    <input type="hidden" name="operacion" id="operacion" value="Añadir ingredientes">
    <fieldset>
        <legend>Datos personales</legend>
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required size="20">

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required size="5">

        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" required size="30">

        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" required size="10">

        <label for="vegetariana">Vegetariana</label>
        <input type="checkbox" name="vegetariana" id="vegetariana">
    </fieldset>
    <input type="submit" value="Enviar">
</form>
<?php
fin_html();
?>