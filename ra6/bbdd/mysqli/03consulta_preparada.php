<?php
/*
    Consultas preparadas con datos procedentes de un formulario
    -----------------------------------------------------------

    1º Ponemos un formulario HTML

    2º Recogemos los datos que hayan enviado

    3º Creamos la cadena con la cláusula WHERE parametrizada

    4º Creamos la consulta preparada

    5º Vinculamos los datos.

    6º Ejecutar la consulta

    7º Procesar los resultados
    
*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Consultas preparadas con MySQLi", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>Consultas preparadas/parametrizadas a la BBDD</header>";

// Parámetros para abrir conexión con la base de datos
$servidor_clase = "192.168.12.71";
$puerto_clase = 3306;
$usuario_clase = "rlozano";
$clave = "usuario";
$esquema_clase = $esquema_casa = "rlozano";

try {
    // 1º Abrir la conexión con la BD
    $cbd = new mysqli($servidor_clase, $usuario_clase, $clave, $esquema_clase);

    $categorias = $cbd->query("SELECT id_categoria, descripcion FROM categoria");

}
catch(mysqli_sql_exception $mse) {
    echo "<h3>Error de base de datos</h3>";
    echo "<p>Código: " . $mse->getCode() . "<br>";
    echo "<p>Mensaje: " . $mse->getMessage() . "<br>";
}

if( $_SERVER['REQUEST_METHOD'] == "POST" ) {
    $filtros_saneamiento = ['referencia'        => FILTER_SANITIZE_SPECIAL_CHARS,
                            'descripcion'       => FILTER_SANITIZE_SPECIAL_CHARS,
                            'pvp'               => ['filter'  => FILTER_SANITIZE_NUMBER_FLOAT,
                                                    'flags' => FILTER_FLAG_ALLOW_FRACTION ],
                            'categoria'         => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    $datos_saneados = filter_input_array(INPUT_POST, $filtros_saneamiento, false);

    $filtros_validacion = ['referencia'        => FILTER_DEFAULT,
                           'descripcion'       => FILTER_DEFAULT,
                           'pvp'               => ['filter' => FILTER_VALIDATE_FLOAT,
                                                   'options' => ['min_range' => 1]],
                           'categoria'          => FILTER_DEFAULT
    ];
    $datos_validados = filter_var_array($datos_saneados, $filtros_validacion);

    $datos_validados = array_filter($datos_validados);

    foreach( $datos_validados as $campo => $valor ) {
        if( ctype_digit($valor) ) $tipos[] = "i";
        elseif ( is_float($valor) ) $tipos[] = "d";
        else $tipos[] = "s";

        if( end($tipos) == "s" ) {
            $condiciones[] = "$campo LIKE ?";
            $valores[] = "%" . strtoupper($valor) . "%";
        } 
        else {
            $condiciones[] = "$campo = ?";
            $valores[] = $valor;
        }
    }

    if( isset($condiciones, $valores, $tipos) ) {
        $clausula_where = "WHERE ";
        $clausula_where.= implode(" AND ", $condiciones);

        $tipos_datos = implode("", $tipos);
    }
}

?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Criterio de búsqueda</legend>
        <label for="referencia">Referencia</label>
        <input type="text" name="referencia" id="referencia" size="10"
        <?=isset($datos_validados['referencia']) ? "value = '{$datos_validados['referencia']}'" : ""?>>

        <label for="descripcion">Descripción</label>
        <input type="text" name="descripcion" id="descripcion" size="25"
        <?=isset($datos_validados['descripcion']) ? "value = '{$datos_validados['descripcion']}'" : ""?>>

        <label for="pvp">PVP</label>
        <input type="text" name="pvp" id="pvp" size="5"
        <?=isset($datos_validados['pvp']) ? "value = '{$datos_validados['pvp']}'" : ""?>>

        <label for="categoria">Categoría</label>
        <select name="categoria" id="categoria" size="1">
            <option value="">Sin categoria</option>
<?php
        foreach($categorias as $categoria ) {
            echo "<option value='{$categoria['id_categoria']}'";
            echo isset($datos_validados['categoria']) && $datos_validados['categoria'] == $categoria['id_categoria'] ? "selected>" : ">";
            echo "{$categoria['descripcion']}</option>";
        }
?>
        </select>
        <!--
        <input type="text" name="categoria" id="categoria" size="5"
        
        -->

        <input type="submit" name="operacion" id="operacion" value="Filtrar">
    </fieldset>
</form>

<?php

try {

    // 2º Creo la sentencia preparada
    $sql = "SELECT referencia, descripcion, pvp, und_vendidas ";
    $sql.= "FROM articulo ";
    if( isset($clausula_where) ) {
        $sql.= $clausula_where;
    }

    $stmt = $cbd->prepare($sql);    // Objeto mysqli_stmt

    // 3º Vincular los valores a los parámetros
    // Tipos de datos: Una cadena de caracteres que indica el tipo de datos
    // de cada parámetro usando una única letra y en el orden en el que aparecen
    // en la sentencia
    // Las letras son: s -> cadena, i->nº entero, d-> nº float o double, s->fecha

    if( isset($clausula_where) )     
        $stmt->bind_param($tipos_datos, ...$valores);

    // 4º Ejecutar la sentencia
    $stmt->execute();

    // 5º Obtener los resultados
    $resultset = $stmt->get_result();

    // 6º Procesar los resultados
    // Tenemos las mismas dos formas que antes
    echo "<table><thead><tr><th>Referencia</th><th>Descripción</th><th>PVP</th><th>Und. Vend.</th>";
    echo "<tbody>";
    while( $fila = $resultset->fetch_assoc() ) {
        echo "<tr>";
        echo "<td>{$fila['referencia']}</td>\n";
        echo "<td>{$fila['descripcion']}</td>\n";
        echo "<td>{$fila['pvp']}</td>\n";
        echo "<td>{$fila['und_vendidas']}</td>\n";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "<p>Número de filas: {$resultset->num_rows}</p>";  
    
    // Liberar el conjunto de resultados
    $resultset->close();
    
}
catch(mysqli_sql_exception $mse) {
    echo "<h3>Error de BBDD en la aplicación</h3>";
    echo "<p>Código de error: " . $mse->getCode() . "<br>";
    echo "Mensaje de error: " . $mse->getMessage() . "</br>";
    echo "Archivo: " . $mse->getFile() . "<br>";
    echo "Línea: " . $mse->getLine() . "</p>";
}
finally {
    // Cierro la conexión de BD
    if( $cbd ) $cbd->close();
}
?>