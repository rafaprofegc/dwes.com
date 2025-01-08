<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra6/bbdd/datos_conexion.php");

// Verificar el token
if( isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);

    if( !$payload ) {
        header("Location: 04autenticacion.php");
    }
}
inicio_html("BBDD- Mysqli", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Actualización de los datos de usuario</header>";

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {

    // Leer de la BD los datos actuales
    try {
        $cbd = new mysqli($servidor_bd, $usuario, $clave, $esquema, $puerto);

        $sql = "SELECT nif, nombre, apellidos, email, iban, telefono ";
        $sql.= "FROM cliente ";
        $sql.= "WHERE nif = ?";

        $stmt = $cbd->prepare($sql);
        $stmt->bind_param("s", $payload['nif']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if( $resultado->num_rows == 0 ) {
            throw new mysqli_sql_exception("El cliente solicitado no existe en la BD", 9000);
        }
        $cliente = $resultado->fetch_assoc();
    }
    catch( mysqli_sql_exception $mse) {
        mostrar_error($mse);
    }
    finally {
        $cbd->close();
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
            $cbd = new mysqli($servidor_bd, $usuario, $clave, $esquema, $puerto);

            $sql = "UPDATE cliente SET ";
            $tipos_datos = "";
            foreach($datos_validados as $campo => $dato) {
                if( $dato === Null ) {
                    $sql.= "$campo = ?, ";
                    $valores[] = Null;
                    $tipos_datos.= "s";

                }
                if( $dato ) {
                    $sql.= "$campo = ?, ";
                    $valores[] = $dato;

                    // Averiguamos los tipos de datos
                    if( ctype_digit($dato) ) $tipos_datos.= "i";
                    elseif( is_numeric($dato) ) $tipos_datos.= "d";
                    else $tipos_datos.= "s";
                }
            }
            $sql = rtrim($sql, ", ");

            $sql.= " WHERE nif = ?";
            $valores[] = $payload['nif'];
            $tipos_datos.= "s";

            $stmt = $cbd->prepare($sql);
            $stmt->bind_param($tipos_datos, ...$valores);
            if( $stmt->execute() && $stmt->affected_rows == 1 ) {
                echo "<h3>El usuario ha actualizado sus datos</h3>";
                if( $datos_validados['nif'] != "" && $payload['nif'] != $datos_validados['nif'] ||
                    $datos_validados['email'] != "" && $payload['email'] != $datos_validados['email']) {
                    cerrar_sesion();
                    echo "<p>Tiene que <a href='04autenticacion.php'>autenticarse de nuevo</a></p>";
                }
                else {
                    echo "<p><a href='05pagina_inicio.php'>Volver a la página de inicio</a></p>";
                }
                fin_html();
                exit(0);
            }
            else {
                throw new mysqli_sql_exception("Ha habido un problema en la actualización", 9001);
            }
        }
        catch(mysqli_sql_exception $mse) {
            mostrar_error($mse);
        }
        finally {
            $cbd->close();
        }
    }
}
/*
    

    

    

    
    // Actualizar los datos en la BBDD
    update cliente
    set email = ?, nif = ?, nombre = ?, apellidos = ?, telefono = ?, iban = ?
    where nif = $payload['nif'];

*/
?>