<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Saneamiento y Validación de datos", 
            ["/estilos/general.css", "/estilos/formulario.css"]);

echo "<header>Saneamiento y validación de datos</header>";

if( $_SERVER['REQUEST_METHOD'] == "POST") {
    /* 
    SANEAMIENTO DE DATOS
    --------------------
    1ª Opción: Uso de htmlspecialchars()
    */
    $dni = htmlspecialchars($_POST['dni']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $clave = htmlspecialchars($_POST['clave']);
    $suscripcion = isset($_POST['suscripcion']);
    $sitio = htmlspecialchars($_POST['sitio']);
    $peso = htmlspecialchars($_POST['peso']);
    $edad = htmlspecialchars($_POST['edad']);
    foreach( $_POST['patologias_previas'] as $patologia) {
        $patologias[] = htmlspecialchars($patologia);
    }
    $comentarios = htmlspecialchars($_POST['comentarios']);
    $operacion = htmlspecialchars($_POST['operacion']);

    echo "<h3>Datos filtrados con htmlspecialchars()</h3>";
    echo "<p>Dni: $dni<br>";
    echo "Nombre: $nombre<br>";
    echo "Email: $email<br>";
    echo "Clave: $clave<br>";
    echo "Suscripción: $suscripcion<br>";
    echo "Sitio: $sitio<br>";
    echo "Peso: $peso<br>";
    echo "Edad: $edad<br>";
    echo "Patologías: " . implode(", ", $patologias) . "<br>";
    echo "Comentarios: $comentarios<br>";
    echo "Operación: $operacion</p>";

    /* 2ª Opción: Uso de la función filter_input() */

    $dni2 = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nombre2 = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $email2 = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
    $clave2 = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_SPECIAL_CHARS);
    $suscripcion2 = isset($_POST['suscripcion']);
    $sitio2 = filter_input(INPUT_POST, 'sitio', FILTER_SANITIZE_URL);
    $peso2 = filter_input(INPUT_POST, 'peso', 
                    FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $edad2 = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_NUMBER_INT);
    $patologias2 = filter_input(INPUT_POST, 'patologias_previas', 
                FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $comentarios2 = filter_input(INPUT_POST, 'comentarios', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $operacion2 = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    echo "<h3>Datos filtrados con filter_input()</h3>";
    echo "<p>Dni: $dni2<br>";
    echo "Nombre: $nombre2<br>";
    echo "Email: $email2<br>";
    echo "Clave: $clave2<br>";
    echo "Suscripción: $suscripcion2<br>";
    echo "Sitio: $sitio2<br>";
    echo "Peso: $peso2<br>";
    echo "Edad: $edad2<br>";
    echo "Patologías: " . implode(", ", $patologias2) . "<br>";
    echo "Comentarios: $comentarios2<br>";
    echo "Operación: $operacion2</p>";

    /*
    3ª Opción: Uso de la función filter_input_array() */

    $opciones_filtrado = [
        'dni'                   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'nombre'                => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'email'                 => FILTER_SANITIZE_EMAIL,
        'clave'                 => FILTER_DEFAULT,
        'suscripcion'           => FILTER_DEFAULT,
        'sitio'                 => FILTER_SANITIZE_URL,
        'peso'                  => [
                                    'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
                                    'flags' => FILTER_FLAG_ALLOW_FRACTION
                                   ],
        'edad'                  => FILTER_SANITIZE_NUMBER_INT,
        'patologias_previas'    => [
                                    'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                                    'flags' => FILTER_REQUIRE_ARRAY
                                   ],
        'comentarios'           => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'operacion'             => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ];

    $datos_saneados = filter_input_array(INPUT_POST, $opciones_filtrado);
    echo"<h3>Resultados del saneamiento con filter_input_array()</h3>";
    echo "<p>";
    foreach( $datos_saneados as $clave => $valor ) {
        if( is_array($valor) ) {
            echo "$clave: " . implode(", ", $valor) . "<br>";
        }
        else {
            echo "$clave: $valor<br>";
        }
    }
    echo "</p>";

    /*
    VALIDACIÓN DE FORMATO DE DATOS 
    ------------------------------

    Uso de las funciones filter_input() y filter_input_array() */

    // Por lo general, las cadenas de texto genéricas se sanean
    $dni3 = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nombre3 = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $clave3 = filter_input(INPUT_POST, 'clave', FILTER_DEFAULT);
    $comentarios3 = filter_input(INPUT_POST, 'comentarios', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $operacion3 = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validación de formato email
    $email3 = filter_input(INPUT_POST, 'email', 
        FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);

    // Validación de formato boolean
    $suscripcion3 = filter_input(INPUT_POST, 'suscripcion', FILTER_VALIDATE_BOOLEAN);

    // Validación de formato URL
    $sitio3 = filter_input(INPUT_POST, 'sitio', 
                            FILTER_VALIDATE_URL);
    
    // Validación de formato float con rango incluido.
    $peso3 = filter_input(INPUT_POST, 'peso',
                          FILTER_VALIDATE_FLOAT,
                          Array('options' => ['min_range' => 40, 'max_range' => 150],
                                'flags' => FILTER_NULL_ON_FAILURE));

    $edad3 = filter_input(INPUT_POST, 'edad', 
                            FILTER_VALIDATE_INT,
                            Array( 'options' => ['min_range' => 18, 'max_range' => 80],
                            'flags' => FILTER_NULL_ON_FAILURE));

    
    $patologias3 = filter_input(INPUT_POST, 'patologias_previas', 
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);


    echo "<h3>Datos validados con filter_input()</h3>";
    echo "<p>Dni: $dni3<br>";
    echo "Nombre: $nombre3<br>";
    echo "Email: $email3<br>";
    echo "Clave: $clave3<br>";
    echo "Suscripción: $suscripcion3<br>";
    echo "Sitio: $sitio3<br>";
    echo "Peso: " . ($peso3 == null ? "No ha pasado la validación" : $peso3) . "<br>";
    echo "Edad: $edad3<br>";
    echo "Patologías: " . implode(", ", $patologias3) . "<br>";
    echo "Comentarios: $comentarios3<br>";
    echo "Operación: $operacion3</p>";

    /* 
        VALIDACION DE DATOS CON LÓGICA DE DATOS
        ---------------------------------------
     */
    
     echo "<h3>Validación de datos con lógica de datos</h3>";
     echo "<p>";
    // Matrícula de un coche: /[0-9]{4}[A-Z]{3}/

    // Formato del DNI: 8 dígitos númericos y una letra mayúscula.
    $dni4 = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $exp_reg = "/^[0-9]{7,8}[A-Z]$/";
    if( preg_match($exp_reg, $dni4) ) {
        echo "Dni: $dni4<br>";
    }
    else {
        echo "Dni: NO tiene el formato adecuado<br>";
        $dni4 = null;
    }

     // Nombres de usuario solo con caracteres alfabéticos y dígitos numéricos.
     // Con longitud mínima de 4 y máxima 8
     $nombre4 = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
     if( ctype_alnum($nombre4) && strlen($nombre4) >= 4 && strlen($nombre4) <= 8 ) {
        echo "Nombre: $nombre4<br>";
     }
     else {
        echo "Nombre: NO tiene el formato adecuado<br>";
     }

     /* 
        Clave: Requisitos de complejidad
        Tipos de caracteres: letras mayúsculas, letras minúsculas, dígitos y símbolos gráficos
        Longitud mínima: 6
     */
    $clave4 = htmlspecialchars($_POST['clave']);

    $letras_minusculas = preg_match("/[a-z]/", $clave4);
    $letras_mayusculas = preg_match("/[A-Z]/", $clave4);
    $digitos_numericos = preg_match("/[0-9]/", $clave4);
    $simbolos_graficos = preg_match("/[,.\-;:_\+\*!\$%&\/()=\?\\#@|]/", $clave4);
    
    if( $letras_mayusculas && $letras_minusculas && $digitos_numericos && $simbolos_graficos
        && strlen($clave4) >= 6 ) {
        echo "Clave: La clave es correcta<br>";
    }
    else {
        echo "Clave: NO cumple los requisitos de complejidad<br>";
    }

    $peso4 = filter_input(INPUT_POST, 'peso', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    if( is_numeric($peso4) ) {
        echo "Peso: $peso4<br>";
    }
    else {
        echo "Peso: No tiene formato adecuado<br>";
    }

    $peso_float = floatval($peso4);

    // Validación de los datos de una lista
    $patologias4 = filter_input(INPUT_POST, 'patologias_previas', 
                FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $valores_validos = ["osteoporosis", "diabetes", "colesterol", "arterioesclerosis", "anemia"];

    /* Si la lista solo tiene un valor
    if( in_array( $patologias4, $valores_validos) ) {
        echo "Patologías: $patologias4<br>";
    }
    else {
        echo "Patologías: Error, hay una que no vale<br>";
    }
    */
    $todo_ok = True;
    foreach( $patologias4 as $patologia ) {
        if( !in_array($patologia, $valores_validos) ) {
            $todo_ok = False;
            break;
        }
    }
    if( $todo_ok ) {
        echo "Patologías: " . implode(", ", $patologias4) . "<br>";
    }
    else {
        echo "Patologías: Error. Hay patologías que no son válidas<br>";
    }

    for($i = 0; $i < count($patologias4); $i++) {
        if( !in_array($patologias[$i], $valores_validos) ) {
            unset($patologias[$i]);
        }
    }
    echo "Patologías (solo las buenas): " . implode(", ", $patologias) . "<br>";










    echo "<p><a href='" . $_SERVER['PHP_SELF'] . "'>Introducir otros datos</a></p>";
}
else {
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Introducir sus datos</legend>

        <label for="dni">DNI</label>
        <!-- pattern="[0-9]{7,8}[A-Z]" -->
        <input type="text" name="dni" id="dni" size="10">

        <label for="nombre">Nombre completo</label>
        <input type="text" name="nombre" id="nombre" size="40">

        <label for="email">Email</label>
        <input type="text" name="email" id="email" size="30">

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" size="10">

        <label for="suscripcion">Suscribirte al boletín?</label>
        <input type="checkbox" name="suscripcion" id="suscripcion">

        <label for="sitio">Web personal</label>
        <input type="text" name="sitio" id="sitio" size="30">

        <label for="peso">Peso</label>
        <input type="text" name="peso" id="peso" size="3">

        <label for="edad">Edad (entre 18 y 65)</label>
        <input type="text" name="edad" id="edad" size="3">

        <label for="patologias_previas">Patologías previas</label>
        <select name="patologias_previas[]" id="patologias_previas" multiple size="5">
            <option value="osteoporosis">Osteoporosis</option>
            <option value="verrugas">Diabetes</option>
            <option value="colesterol">Hipercolesterolemia</option>
            <option value="anemia">Anemia</option>
            <option value="arterioesclerosis">Arterioesclerosis</option>
        </select>

        <label for="comentarios">Comentarios</label>
        <textarea rows="4" cols="30" name="comentarios" id="comentarios" 
            placeholder="Escribe sobre ti"></textarea>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>
<?php
}
?>