<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("BBDD - Autenticación de usuario", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Autenticación de usuario</header>";

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {
    // Formulario de autenticación
?>
    <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
        <fieldset>
            <legend>Indique sus credenciales</legend>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="30" require>

            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" size="10" require>
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Abrir sesión">    
    </form>
    <a href='04registro_cliente.php'>No soy cliente y quiero registrarme</a></p>
<?php
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST" && htmlspecialchars($_POST['operacion']) == "Abrir sesión") {
    // Autenticación de usuario
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $clave = $_POST['clave'];

    if( !$email ) {
        echo "<h3>Error. El email no tiene formato correcto</h3>";
        echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver a intentarlo</a></p>";
        fin_html();
        exit(1);
    }

    // Credenciales de usuario
    // Proceso de autenticación
    try {
        $servidor = "192.168.12.71";
        $usuario = "rlozano";
        $clave_bd = "usuario";
        $esquema = "rlozano";
        $puerto = 3306;

        $cbd = new mysqli($servidor, $usuario, $clave_bd, $esquema, $puerto);

        $email = cbd->escape_string($email);

        $sql = "SELECT nombre, apellidos, email, clave ";
        $sql.= "WHERE email = '$email' AND clave='$clave'";

        $sql.= "WHERE email = '$email' AND clave='' OR 'True'";
        
        
        $resultado = $cbd->query($sql);

    }
    catch(mysqli_sql_exception $mse) {

    }

