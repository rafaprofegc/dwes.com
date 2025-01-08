<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");

// Verificar el token
if( isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);

    if( !$payload ) {
        header("Location: 04autenticacion.php");
    }
}
else {
    header("Location: 04autenticacion.php");
}

inicio_html("BBDD- Mysqli", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Actualización de los datos de usuario</header>";

        $dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
        //$dsn = "mysql:host=cpd.iesgrancapitan.org;dbname=rlozano;port=9992;charset=utf8mb4";
        $usuario = "rlozano";
        $clave = "usuario";
        $opciones = [
            PDO::ATTR_ERRMODE                       => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE            => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES              => false
        ];

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {

    // Leer de la BD los datos actuales
    try {
        $pdo = new PDO($dsn, $usuario, $clave, $opciones);

        $sql = "SELECT nif, nombre, apellidos, iban, telefono, email FROM cliente ";
        $sql.= "WHERE nif = :nif";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":nif", $payload['nif']);
        if( $stmt->execute() && $stmt->rowCount() == 1 ) {
            $cliente = $stmt->fetch();
        }
        else {
            echo "<h3>El cliente no existe</h3>";
            echo "<p><a href='04autenticacion.php'>Ir al formulario de autenticación </a></p>";
            exit(1);
        }
        
    }
    catch( PDOException $pdoe) {
        mostrar_error($pdoe);
    }
    finally {
        $stmt = null;
        $pdo = null;
    }
    
    // Monto un sticky form con los datos actuales
    // como valores por defecto.

?>
    <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
        <fieldset>
            <legend>Datos personales del cliente</legend>
            <label for="nif">Nif</label>
            <input type="text" name="nif" id="nif" size="10" 
                value="<?=$cliente['nif']?>" pattern="[0-9]{8}[A-Za-z]">

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" size="15" 
                value="<?=$cliente['nombre']?>">
            
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" size="20" 
                value="<?=$cliente['apellidos']?>">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="20" 
                value="<?=$cliente['email']?>">

            <label for="iban">IBAN</label>
            <input type="text" name="iban" id="iban" size="25" 
                value="<?=$cliente['iban']?>" pattern="ES[0-9]{2} [0-9]{4} [0-9]{4} [0-9]{2} [0-9]{10}">

            <label for="telefono">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" size="10" 
                value="<?=$cliente['telefono']?>" value="[0-9]{9}">

        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Actualizar mis datos">
    </form>
<?php
    fin_html();
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST") {
    $operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
    if( $operacion == "Actualizar mis datos" ) {
        // Sanear y validar los datos
        $filtros_saneamiento = ['nif'       => FILTER_SANITIZE_SPECIAL_CHARS,
                                'nombre'    => FILTER_SANITIZE_SPECIAL_CHARS,
                                'apellidos' => FILTER_SANITIZE_SPECIAL_CHARS,
                                'email'     => FILTER_SANITIZE_EMAIL,
                                'iban'      => FILTER_SANITIZE_SPECIAL_CHARS,
                                'telefono'  => FILTER_SANITIZE_NUMBER_INT];

        $datos_saneados = filter_input_array(INPUT_POST, $filtros_saneamiento);

        $filtros_validacion = ['nif'       => FILTER_DEFAULT,
                               'nombre'    => FILTER_DEFAULT,
                               'apellidos' => FILTER_DEFAULT,
                               'email'     => FILTER_VALIDATE_EMAIL,
                               'iban'      => FILTER_DEFAULT,
                               'telefono'  => ['filter' => FILTER_VALIDATE_INT,
                                               'flags' => FILTER_NULL_ON_FAILURE] ];

        $datos_validados = filter_var_array($datos_saneados, $filtros_validacion);

        if( $datos_validados['nif'] ) {
            $datos_validados['nif'] = preg_match("/[0-9]{8}[a-zA-Z]/", $datos_validados['nif'] ) ?
                                                $datos_validados['nif'] : False;
        }

        if( $datos_validados['iban'] ) {
            $datos_validados['iban'] = preg_match("/ES[0-9]{2} [0-9]{4} [0-9]{4} [0-9]{2} [0-9]{10}/", 
                                        $datos_validados['iban'] ) ? $datos_validados['iban'] : False;
        }

        try {
            $pdo = new PDO($dsn, $usuario, $clave, $opciones);
            
            $sql = "UPDATE cliente SET ";
            // campo = :par, campo = :par, ...
            foreach($datos_validados as $clave => $valor ) {
                if( $valor === Null || $valor ) {
                    $campos[$clave] = "$clave = :$clave";
                    $valores[$clave] = $valor;
                }
            }
            $parametros = array_values($campos);
            $updates = implode(", ", $parametros);
            
            $clausula_where = " WHERE nif = :nif_anterior";
            $sql.= $updates . $clausula_where;

            $stmt = $pdo->prepare($sql);
            //$campos['nif_anterior'] = $payload['nif'];
            foreach( $valores as $clave => $valor ) {
                $stmt->bindValue(":$clave", $valor);
            }
            $stmt->bindValue(':nif_anterior', $payload['nif']);
            

            if( $stmt->execute() && $stmt->rowCount() == 1 ) {
                echo "<h3>El usuario ha actualizado sus datos</h3>";
                if( $datos_validados['nif'] != "" && $payload['nif'] != $datos_validados['nif'] ||
                    $datos_validados['email'] != "" && $payload['email'] != $datos_validados['email']) {
                    echo "<p>Tiene que <a href='04autenticacion.php'>autenticarse de nuevo</a></p>";
                    //cerrar_sesion();
                }
                else {
                    echo "<p><a href='06pagina_inicio.php'>Volver a la página de inicio</a></p>";
                }
                fin_html();
                exit(0);
            }
            else {
                // Lanzar la excepción
                throw new PDOException("No se ha actualizado el cliente", 9001);
            }
        }
        catch(PDOException $pdoe) {
            mostrar_error($pdoe);
        }
        finally {
            $stmt = null;
            $pdo = null;
        }
    }
}
?>