<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

define("TAMAYO_MAXIMO_KB",200);

inicio_html("Recuperación RA2 y 3", 
  ["/estilos/general.css", "/estilos/formulario.css", "/estilos/tablas.css"]);

$marcas = ['f' => "Fiat", 'o' => "Opel", 'm' => "Mercedes" ];
$tipos = ['T' => "Turismo", 'F' => "Furgoneta"];

function presentar_formulario(array $marcas) {
?>
<h2>Formulario de búsqueda de coches</h2>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data"> 
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=TAMAYO_MAXIMO_KB*1024?>">

    <fieldset>
        <legend>Datos del vehículo a buscar</legend>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" size="30" required>

        <label for="tipo">Tipo vehículo</label>
        <div>
            <input type="radio" name="tipo" id="tipo1" value="T">Turismo<br>
            <input type="radio" name="tipo" id="tipo2" value="F">Furgoneta
        </div>
        
        <label for="marca">Marca</label>
        <select name="marca" id="marca" size="1">
            <option value="">Elige una marca</option>
<?php
        foreach( $marcas as $marca => $nombre ) {
            
            echo "<option value='$marca'>$nombre</option>";
        }
?>
        </select>

        <label for="antiguedad">Antigüedad (años)</label>
        <input type="text" name="antiguedad" id="antiguedad" size="2">

        <label for="itv">ITV pasada</label>
        <input type="checkbox" name="itv" id="itv" checked>

        <label for="vd">Archivo de búsqueda</label>
        <input type="file" name="vd" id="vd" accept="text/csv">

    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>
<?php
}

function comprobar_archivo() {
    // Comprobar la subida del archivo
    if( $_FILES['vd']['error'] == UPLOAD_ERR_FORM_SIZE ){        
        return 1;
    }

    // Comprueba el tipo mime
    $tipos_mime_validos = ["text/csv"];

    $tipo_mime_subido = $_FILES['vd']['type'];

    $tipo_mime_archivo = mime_content_type($_FILES['vd']['tmp_name']);
    
    $puntero_info = finfo_open(FILEINFO_MIME_TYPE);
    $tipo_mime_info = finfo_file($puntero_info, $_FILES['vd']['tmp_name']);

    if( !in_array($tipo_mime_subido, $tipos_mime_validos) or
        !in_array($tipo_mime_archivo, $tipos_mime_validos ) or
        !in_array($tipo_mime_info, $tipos_mime_validos) ) {

        return 2;
            
    }

    if( $_FILES['vd']['error'] != UPLOAD_ERR_OK ) {
        return 3;
    }

    return 0;

}

function sanear_y_validar() {
    global $tipos;
    global $marcas;

    // En este punto el tipo mime es el esperado y tiene
    // un tamaño dentro del límite
    // Sanear los datos
    $filtro_saneamiento = ['email'          => FILTER_SANITIZE_EMAIL,
                            'tipo'           => FILTER_SANITIZE_SPECIAL_CHARS,
                            'marca'          => FILTER_SANITIZE_SPECIAL_CHARS,
                            'antiguedad'     => FILTER_SANITIZE_NUMBER_INT,
                            'itv'            => FILTER_DEFAULT
    ];
    $datos_saneados = filter_input_array(INPUT_POST, $filtro_saneamiento);

    // Validar los datos
    $filtro_validacion = ['email'           => FILTER_VALIDATE_EMAIL,
                          'tipo'            => FILTER_DEFAULT,
                          'marca'           => FILTER_DEFAULT,
                          'antiguedad'      => ['filter' => FILTER_VALIDATE_INT,
                                                'options' => ['min_range' => 1,
                                                              'max_range' => 5  ]],
                          'itv'             => FILTER_VALIDATE_BOOL
    ];
    $datos_validados = filter_var_array($datos_saneados, $filtro_validacion);

    // Validar con lógica de negocio

    if( !array_key_exists( $datos_validados['tipo'], $tipos) ) {
        $datos_validados['tipo'] = false;
    }

    if( !array_key_exists( $datos_validados['marca'], $marcas)) {
        $datos_validados['marca'] = false;
    }

    // Si falta algún dato, se termina
    $array_filtrado = array_filter($datos_validados);

    //return count($array_filtrado) == 5 or 
    //          count($array_filtrado) == 4 and !$datos_validados['itv'];
    
    if( count($array_filtrado) < 4 or
        count($array_filtrado) == 4 and $datos_validados['itv']) {
        return false;
    }
    else {
        return $datos_validados;
    }
   
}

function mostrar_resultados($datos_validados) {
    global $tipos, $marcas;

    // Abrir archivo y comprobar los datos con los del archivo
    $archivo = fopen($_FILES['vd']['tmp_name'], "r");
    $linea = fgetcsv($archivo);
    echo <<<TABLA
    <table>
        <thead>
            <tr>
                <th>{$linea[0]}</th>
                <th>{$linea[1]}</th>
                <th>{$linea[2]}</th>
                <th>{$linea[3]}</th>
            </tr>
        </thead>
        <tbody>
    TABLA;

    while( $linea = fgetcsv($archivo)) {
        if($linea[0] == $tipos[$datos_validados['tipo']] and 
            $linea[1] == $marcas[$datos_validados['marca']] and 
            $linea[2] == $datos_validados['antiguedad'] and 
            $linea[3] == ($datos_validados['itv'] ? "Si" : "No") ) {
            echo <<<FILA
                <tr>
                    <td>$linea[0]</td>
                    <td>$linea[1]</td>
                    <td>$linea[2]</td>
                    <td>$linea[3]</td>
                </tr>
            FILA;
        }
    }
    echo "</tbody></table>";
}

echo "<header>Recuperación RA2-3</header>";

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {
    // Presentar el formulario
    presentar_formulario($marcas);
}

if( $_SERVER['REQUEST_METHOD'] == "POST") {

    // Compruebo la subida de archivo
    $error = comprobar_archivo();
    switch( $error ) {
        case 1 : {
            $mensaje_error = "El archivo ha superado el tamaño máximo";
            break;
        }
        case 2 : {
            $mensaje_error = "El tipo mime tiene que ser CSV";
            break;
        }
        case 3 : {
            $mensaje_error = "No hay archivo en la subida";
            break;
        }
    }
    if( $error ) {
        echo "<h3>$mensaje</h3>";
        fin_html();
        exit($error);
    }

    $datos_validados = sanear_y_validar();
    if( !$datos_validados ) {
        echo "<h3>ErrorLos datos no están validados</h3>";   
        exit(4);
    }

    mostrar_resultados($datos_validados);

    echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver al formulario de búsqueda</a><p>";    
}

fin_html();
?>