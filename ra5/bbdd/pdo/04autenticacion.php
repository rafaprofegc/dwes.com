<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");

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
    <a href='05registro_cliente.php'>No soy cliente y quiero registrarme</a></p>
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

    try {
        $dsn = "mysql:host=192.168.12.71;port=3306;dbname=rlozano;charset=utf8mb4";
        $usuario = "rlozano";
        $clave = "usuario";
        $opciones = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false
        ];

        $pdo = new PDO($dsn, $usuario, $clave, $opciones);

        $sql = "SELECT nif, nombre, apellidos, email, clave FROM cliente WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);

        if( $stmt->execute() ) {
            if( $stmt->rowCount() == 1 ) {
                $usuario = $stmt->fetch();
                if( password_verify($clave, $usuario['clave'])) {
                    $payload = ['nif'       => $usuario['nif'],
                                'nombre'    => "{$usuario['nombre']} {$usuario['apellidos']}",
                                'email'     => $usuario['email'] ];
                    $jwt = generar_token($payload);
                    setcookie("jwt", $jwt, time() + 24 * 60 );

                    echo "<h3>Autenticación de usuario con éxito</h3>";
                    echo "<p><a href='06pagina_inicio.php'>Ir a la zona autenticada</a></p>";
                    exit(0);
                }
            }
            else {
                $e = new Exception("No existe el usuario", 9000);
                mostrar_error($e);
            }
        }
        else {
            echo "<h3>Error al ejecutar la consulta</h3>";
        }
    }
    catch(PDOException $pdoe) {
        mostrar_error($pdoe);
    }
    finally {
        $pdo = null;
        $stmt = null;

    }
}
