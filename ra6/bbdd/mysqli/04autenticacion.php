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

        $sql = "SELECT nif, nombre, apellidos, email, clave ";
        $sql.= "FROM cliente ";
        $sql.= "WHERE email = ?";

        $stmt = $cbd->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if( $resultado->num_rows == 1 ) {
            // El usuario existe
            // Compruebo la clave
            $cliente = $resultado->fetch_assoc();

            if( password_verify($clave, $cliente['clave']) ) {
                // Usuario autenticado
                
                $payload = ['nif'       => $cliente['nif'],
                            'nombre'    => "{$cliente['nombre']} {$cliente['apellidos']}" ,
                            'email'     => $cliente['email'] ];
                $jwt = generar_token($payload);
                setcookie("jwt", $jwt, time() + 30 * 60);

                echo "<h3>Cliente autenticado con éxito</h3>";
                echo "<p>¡Bienvenido, {$payload['nombre']}! Desde aquí puede acceder a ";
                echo "<a href='05pagina_inicio.php'>nuestro catálogo</a></p>";
                fin_html();
                exit(0);
            }
            else {
                // Clave no válida
                echo "<h3>Error en la aplicación</h3>";
                echo "<p>La clave no es válida.<br>";
                echo "<a href='{$_SERVER['PHP_SELF']}'>Inténtelo de nuevo</a></p>";
                fin_html();
                exit(2);    
            }

        }
        else {
            echo "<h3>Error en la aplicación</h3>";
            echo "<p>El usuario $email no existe.<br>";
            echo "<a href='{$_SERVER['PHP_SELF']}'>Inténtelo de nuevo</a></p>";
            fin_html();
            exit(1);
        }

    }
    catch(mysqli_sql_exception $mse) {
        echo "<h3>Error en la aplicación</h3>";
        echo "<p>Código de error: " . $mse->getCode() . "<br>";
        echo "<p>Mensaje de error: " . $mse->getMessage() . "<br>";
    }
}
