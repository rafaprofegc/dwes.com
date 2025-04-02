<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

$proyectos = [ 'ap' => "Agua potable",
               'ep' => "Escuela de primaria",
               'ps' => "Placas solares",
               'cm' => "Centro médico"
];

inicio_html("Actividad 6 - ONGs", ["/estilos/general.css", "/estilos/formulario.css", "/estilos/tablas.css"]);

// Antes del formulario, comprobamos si hay una petición
// para recoger los datos
$datos_validados = [];

if( $_SERVER['REQUEST_METHOD'] == "POST") {

    // Sanear los datos
    $filtro_sanear = [ 'email' => FILTER_SANITIZE_EMAIL,
                       'registro' => FILTER_DEFAULT,
                       'cantidad' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 
                                      'flags' => FILTER_FLAG_ALLOW_FRACTION],
                       'proyecto' => FILTER_SANITIZE_SPECIAL_CHARS,
/*                                      
                       'proyecto' => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                                      'flags' => FILTER_REQUIRE_ARRAY ],
*/
                       'propuesta' => FILTER_SANITIZE_SPECIAL_CHARS];

    $datos_saneados = filter_input_array(INPUT_POST, $filtro_sanear);

    $filtro_validacion = [ 'email' => FILTER_VALIDATE_EMAIL,
                           'registro' => FILTER_VALIDATE_BOOL,
                           'cantidad' => ['filter' => FILTER_VALIDATE_FLOAT, 
                                          'options' => ['default' => 20, 'min_range' => 10] ],
                           'proyecto' => FILTER_DEFAULT,
                           'propuesta' => FILTER_DEFAULT];

    $datos_validados = filter_var_array($datos_saneados, $filtro_validacion);

    $mensajes = [];

    if( !$datos_validados['email'] ) $mensajes['email'] = "El email no es correcto";
    if( !$datos_validados['cantidad'] ) $mensajes['cantidad'] = "La cantidad no es correcta";


    // Validación con lógica de negocio
    if( $datos_validados['proyecto'] != "" and !array_key_exists($datos_validados['proyecto'], $proyectos) ) {
        // El proyecto no es válido
        $mensajes['proyecto'] = "El proyecto no es válido";
    }

    if( $datos_validados['proyecto'] == "" and trim($datos_validados['propuesta']) == "" ) {
        $mensajes['propuesta'] = "Hay que elegir un proyecto si no haces una propuesta";
    }

    
    if( count($mensajes) == 0 ) {
        // No hay errores de validación. Se visualizan los datos
        $registro = $datos_validados['registro'] ? "Se autoriza" : "No se autoriza";
        $proyecto = $datos_validados['proyecto'] ? $proyectos[$datos_validados['proyecto']] : "Se incluye una propuesta";
        echo <<<DATOS
        <table><thead><tr>
            <th>Email</th><th>Registro</th><th>Cantidad</th><th>Proyecto</th><th>Propuesta</th>
        </tr>
        <tbody><tr>
            <td>{$datos_validados['email']}</td>
            <td>{$registro}</td>
            <td>{$datos_validados['cantidad']}€</td>
            <td>{$proyecto}</td>
            <td>{$datos_validados['propuesta']}</td>
        </tr></tbody>
        </table>
        DATOS;
    }
    else {
        // Hay errores de validación. Visualizamos los mensajes de error
        echo "<p>";
        foreach($mensajes as $mensaje) {
            echo "$mensaje<br>";
        }
        echo "</p>";
    }
}

?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Datos de la donación</legend>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" size="25" required
            <?=isset($datos_validados['email']) ? "value='{$datos_validados['email']}'" : "" ?>
        >

        <label for="registro">Autorizo registro</label>
        <input type="checkbox" name="registro" id="registro" value="True"
        <?=(isset($datos_validados['registro']) and $datos_validados['registro']) ? "checked" : ""?>
         >

        <label for="cantidad">Cantidad</label>
        <input type="text" name="cantidad" id="cantidad" required size="5"
        <?=isset($datos_validados['cantidad']) ? "value='{$datos_validados['cantidad']}'" : "" ?>
        >

        <label for="proyecto">Proyecto</label>
        <select size="1" name="proyecto" id="proyecto">
            <option value="">Despliega la lista y elige uno</option>
<?php
        foreach( $proyectos as $clave => $valor) {
            echo "<option value='$clave'";
            //echo in_array($clave, $datos_validados['proyecto']) ? " selected" : "";
            echo (isset($datos_validados['proyecto']) and $datos_validados['proyecto'] == $clave) ? " selected" : "";
            echo ">$valor</option>" . PHP_EOL;
        }            
?>
        </select>
        
        <label for="propuesta">Propuesta</label>
        <textarea name="propuesta" id="propuesta" cols="30" rows="3">
<?=isset($datos_validados['propuesta']) ? $datos_validados['propuesta'] : "Escribe tu propuesta"?></textarea>
    </fieldset>

    <input type="submit" name="operacion" value="Enviar">
</form>
<?php
fin_html();
?>