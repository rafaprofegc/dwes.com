<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("BBDD - Registro de nuevos clientes", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Registro de nuevo cliente</header>";
if( $_SERVER['REQUEST_METHOD'] == "GET") {
    // Formulario de registro
?>
    <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
        <fieldset>
            <legend>Datos personales del nuevo usuario</legend>
            <label for="nif">Nif</label>
            <input type="text" name="nif" id="nif" size="10" require
                pattern="[0-9]{8}[A-Za-z]">

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" size="20" require>

            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" size="30" require>

            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" size="10" require>

            <label for="iban">IBAN</label>
            <input type="text" name="iban" id="iban" size="25" require
                pattern="ES[0-9]{2} [0-9]{4} [0-9]{4} [0-9]{2} [0-9]{10}"
                value="ES00 1234 5678 90 1234567890">

            <label for="telefono">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" size="10">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="30" require>

            <label for="acepto"></label>
            <input type="checkbox" name="acepto" id="acepto" require>
            Acepto la política de privacidad
            <a href=''>Política de tratamiento de datos personales</a>

        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Registrarme">
    </form>
<?php
}  
elseif( $_SERVER['REQUEST_METHOD'] == "POST") {
    // Inserción del nuevo cliente
    $filtros_saneamiento = ['nif'       => FILTER_SANITIZE_SPECIAL_CHARS,
                            'nombre'    => FILTER_SANITIZE_SPECIAL_CHARS,
                            'apellidos' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'clave'     => FILTER_DEFAULT,
                            'iban'      => FILTER_SANITIZE_SPECIAL_CHARS,
                            'telefono'  => FILTER_SANITIZE_NUMBER_INT,
                            'email'     => FILTER_SANITIZE_EMAIL];

    $datos_saneados = filter_input_array(INPUT_POST, $filtros_saneamiento, false);

    // Validación de datos
    $datos_saneados['nif'] = preg_match("/[0-9]{8}[a-zA-Z]/", $datos_saneados['nif']) ? $datos_saneados['nif'] : False;
    //if( !preg_match("//", $datos_saneados['nif']) ) $datos_saneados['nif'] = False;

    $datos_saneados['iban'] = preg_match("/ES[0-9]{2} [0-9]{4} [0-9]{4} [0-9]{2} [0-9]{10}/", $datos_saneados['iban']) ? $datos_saneados['iban'] : False;

    $datos_saneados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL);

    $datos_saneados['telefono'] = preg_match("/[0-9]{9}/", $datos_saneados['telefono']) ? $datos_saneados['telefono'] : Null;

    $campos_obligatorios = $datos_saneados['telefono'] ? 7 : 6;

    $datos_validados = array_filter($datos_saneados);

    if( count($datos_validados) != $campos_obligatorios)  {
        echo "<h3>Error. No se han rellenado todos los datos</h3>";
        echo "<p><a href='{$_SERVER['PHP_SELF']}'>Vuelva a intentarlo</a></p>";
        fin_html();
        exit(1);
    }

    // Tenemos todos los campos y podemos insertar el cliente
    try {

        $servidor = "192.168.12.71";
        $usuario = "rlozano";
        $clave = "usuario";
        $esquema = "rlozano";
        $puerto = 3306;

        $cbd = new mysqli($servidor, $usuario, $clave, $esquema, $puerto);

        $sql = "INSERT INTO cliente (nif, nombre, apellidos, clave, iban, telefono, email, ventas) ";
        $sql.= "VALUES(?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $cbd->prepare($sql);

        $datos_saneados['clave'] = password_hash($datos_saneados['clave'], PASSWORD_DEFAULT);
        $valores = array_values($datos_saneados);
        $stmt->bind_param("sssssss", ...$valores);

        if( $stmt->execute() && $stmt->affected_rows == 1 ) {
            echo "<h3>¡Bienvenido, {$datos_validados['nombre']}!</h3>";
            echo "<p>Ha sido registrado como cliente y ";
            echo "ya puede <a href='04autenticacion.php'>acceder a la autenticación</a></p>";
            fin_html();
            exit(0);
        }
        else {
            echo "<h3>Error. No se ha realizado el registro.</h3>";
            echo "<p>Revise los datos y <a href='{$_SERVER['PHP_SELF']}'>vuelva a intentarlo</a></p>";
            fin_html();
            exit(1);
        }       
    }
    catch(mysqli_sql_exception $mse) {
        echo "<h3>Error de la aplicación</h3>";
        echo "<p>Código de error: " . $mse->getCode() . "<br>";
        echo "<p>Mensaje de error: " . $mse->getMessage() . "<br>";
        echo "<p>Revise los datos y <a href='{$_SERVER['PHP_SELF']}'>vuelva a intentarlo</a></p>";

    }
    finally {
        $cbd->close();
    }    
}

fin_html();
?>
