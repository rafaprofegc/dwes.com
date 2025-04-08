<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

// Cierre de sesión
if( $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['operacion']) && 
    filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS) == "cs") {
    //PENDIENTE
}

inicio_html("Recuperación RA4 - Autenticación", ["/estilos/general.css", "/estilos/formulario.css"]);
// Mensaje de error
if( isset($_SESSION['error']) ) {
    echo "<div style='color:red;font-size:16pt;text-align:center;'>" . $_SESSION['error'] . "</div>";
}

// Formulario de autenticación
?>
<form method="POST" action="inicio00.php">
    <fieldset>
        <legend>Autenticación de usuario</legend>

        <label for="dni">Dni</label>
        <input type="text" name="dni" id="dni" size="10" required>

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" size="10" required>

        <label></label>
        <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
    </fieldset>
    
</form>

<?php
fin_html();
?>