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

inicio_html("PDO - Consultas preparadas", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>Consultas preparadas/parametrizadas a la BBDD</header>";

// Parámetros para abrir conexión con la base de datos
$dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
//$dsn = "mysql:host=cpd.iesgrancapitan.org;port=9992;dbname=rlozano;charset=utf8mb4";
$usuario = "rlozano";
$clave = "usuario";
$opciones = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES      => false
            ];

try {
    // 1º Abrir la conexión con la BD
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);

    $stmt = $pdo->query("SELECT id_categoria, descripcion FROM categoria");
    if($stmt->execute() ) {
        $categorias = $stmt->fetchAll();
    }
    else {
        $categorias = [];
    }
}
catch(PDOException $pdoe) {
    mostrar_error($pdoe);
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
        if( is_float($valor) ) $tipo = "d";
        else $tipo = "s";


        if( $tipo == "s" ) {
            $condiciones[] = "$campo LIKE :$campo";
            $valores[":$campo"] = "%" . $valor . "%";
        } 
        else {
            $condiciones[] = "$campo = :$campo";
            $valores[":$campo"] = $valor;
        }
    }

    if( isset($condiciones, $valores) ) {
        $clausula_where = "WHERE ";
        $clausula_where.= implode(" AND ", $condiciones);
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
    $stmt = $pdo->prepare($sql);

    // 3º Vincular los valores a los parámetros
    // $valores[':referencia'] = "%CARN%";
    // $valores[':descripcion'] = "%kg%";
    // $valores[':pvp'] = 2.25;
    // ...
    
    if( isset($valores) ) {
        foreach($valores as $nombre_parametro => $valor) {
            $stmt->bindValue($nombre_parametro, $valor);
        }
    }
    
    //$stmt->bindParam(':referencia', $valores[':referencia']);
    //$stmt->bindParam(':descripcion', $valores[':descripcion']);
    
    // 4º Ejecutar la sentencia
    if( $stmt->execute() ) {
        // 5º Procesar los resultados
        // Tenemos las mismas dos formas que antes
        echo <<<TABLE
            <table>
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Descripción</th>
                        <th>PVP</th>
                        <th>Und. Vend.</th>
                    </tr>
                </thead>
                <tbody>
        TABLE;
        while( $fila = $stmt->fetch() ) {
            echo "<tr>";
            echo "<td>{$fila['referencia']}</td>\n";
            echo "<td>{$fila['descripcion']}</td>\n";
            echo "<td>{$fila['pvp']}</td>\n";
            echo "<td>{$fila['und_vendidas']}</td>\n";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "<p>Número de filas: " . $stmt->rowCount() . "</p>";
    }
    else {
        echo "<h3>Error en la consulta</h3>";
    }    
}
catch(PDOException $pdoe) {
    mostrar_error($pdoe);
}
finally {
    // Cierro la conexión de BD
    $pdo = null;
}
?>