<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once("datos_conexion.php");

inicio_html("BBDD - MySQLi", ["/estilos/general.css"]);
echo "<header>Baja de cliente</header>";

// Verificar el token
if( isset($_COOKIE['jwt']) ) {
    $payload = $_COOKIE['jwt'];

    if( !$payload) {
        header("Location: 04autenticacion.php");
    }
}

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    echo "<h3>Baja de cliente</h3>";
    echo "<p>Cliente {$payload['nombre']} quiere darse de baja</p>";
    echo "<p>Por favor, confirme que quiere darse de baja definitiva...</p>";
?>

<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="submit" name="operacion" id="operacion" value="Darme de baja">
</form>
<?php
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST") {
    $operacion = filter_input(INPUT_POST, "operacion", FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        $cbd = new mysqli($servidor_bd, $usuario, $clave, $esquema, $puerto);

        $sql = "DELETE FROM cliente WHERE nif = ?";
        $stmt = $cbd->prepare($sql);
        $stmt->bind_param("s", $payload['nif']);
        if( $stmt->execute() && $stmt->affected_rows == 1 ) {
            echo "<h3>Cliente {$payload['nombre']} ha sido dado de baja.</h3>";
            cerrar_sesion();
        }
    }
    catch( mysqli_sql_exception $mse) {
        mostrar_error($mse);
    }
}
fin_html();
?>