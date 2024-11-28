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

inicio_html("Consultas simples con MySQLi", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>Consultas preparadas/parametrizadas a la BBDD</header>";

// Parámetros para abrir conexión con la base de datos
$servidor_clase = "192.168.12.71";
$puerto_clase = 3306;
$usuario_clase = "rlozano";
$clave = "usuario";
$esquema_clase = $esquema_casa = "rlozano";

?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Criterio de búsqueda</legend>
        <label for="referencia">Referencia</label>
        <input type="text" name="referencia" id="referencia" size="10">

        <label for="descripcion">Descripción</label>
        <input type="text" name="descripcion" id="descripcion" size="25">

        <label for="pvp">PVP</label>
        <input type="text" name="pvp" id="pvp" size="5">

        <label for="categoria">Referencia</label>
        <input type="text" name="categoria" id="categoria" size="5">

        <input type="submit" name="operacion" id="operacion" value="Filtrar">
    </fieldset>
</form>

<?php
if( $_SERVER['REQUEST_METHOD'] == "POST" ) {
    $referencia = filter_input(INPUT_POST, 'referencia', FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $pvp = filter_input(INPUT_POST, 'pvp', FILTER_SANITIZE_NUMBER_FLOAT);
    $pvp = filter_var($pvp, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS);

    if( empty($referencia) ) {
        
    }
}

try {
    // 1º Abrir la conexión con la BD
    $cbd = new mysqli($servidor_clase, $usuario_clase, $clave, $esquema_clase);

    // 2º Creo la sentencia preparada
    $sql = "SELECT referencia, descripcion, pvp, und_vendidas ";
    $sql.= "FROM articulo ";
    $sql.= "WHERE pvp > ? AND categoria = ? AND und_vendidas > ?";

    $stmt = $cbd->prepare($sql);    // Objeto mysqli_stmt

    $pvp_minimo = 4.75;
    $categoria = "CARN";
    $und_vendidas = 5;

    $categoria = $cbd->escape_string($categoria);

    // 3º Vincular los valores a los parámetros
    // Tipos de datos: Una cadena de caracteres que indica el tipo de datos
    // de cada parámetro usando una única letra y en el orden en el que aparecen
    // en la sentencia
    // Las letras son: s -> cadena, i->nº entero, d-> nº float o double, s->fecha

    $tipos ="dsi";
    $stmt->bind_param($tipos, $pvp_minimo, $categoria, $und_vendidas);

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