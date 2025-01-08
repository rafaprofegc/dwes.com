<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");

inicio_html("BBDD - PDO", ["/estilos/general.css"]);
echo "<header>Baja de cliente</header>";

// Verificar el token
if( isset($_COOKIE['jwt']) ) {
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);

    if( !$payload) {
        header("Location: 04autenticacion.php");
    }
}
else {
    header("Location: 04autenticacion.php");
}

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    echo "<h3>Baja de cliente</h3>";
    echo "<p>Cliente {$payload['nombre']} quiere darse de baja</p>";
    echo "<p>Por favor, confirme que quiere darse de baja definitiva...</p>";
?>

<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="submit" name="operacion" id="operacion" value="Darme de baja">
</form>
<p><a href="05pagina_inicio.php">No quiero de darme de baja. Me voy a la zona autenticada</a></p>
<?php
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST") {
    $operacion = filter_input(INPUT_POST, "operacion", FILTER_SANITIZE_SPECIAL_CHARS);

    if( $operacion == "Darme de baja" ) {
        try {
            // Damos de baja
            $dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
            $usuario = "rlozano";
            $clave = "usuario";

            $opciones = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES      => false
            ];

            $pdo = new PDO($dsn, $usuario, $clave, $opciones );

            $sql = "DELETE FROM cliente WHERE nif = :nif";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nif', $payload['nif']);

            if( $stmt->execute() && $stmt->rowCount() == 1 ) {
                cerrar_sesion();
                echo "<h3>El clienre {$payload['nombre']} ha sido dado de baja</h3>";
                echo "<p>Si lo desea pueda <a href='05registro_cliente.php'>volver a registrarse</a></p>";
            }
            else {
                throw new PDOException("Error al eliminar la cuenta de cliente", 9002);
            }
        }
        catch(PDOException $pdoe) {
            mostrar_error($pdoe);
        }
    }
}
fin_html();
?>