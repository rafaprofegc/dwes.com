<?php
/*
    Consultas preparadas
    --------------------

    Cuando en una sentencia SQL necesitamos usar datos que ha generado la propia aplicación:
        - Procedentes de un formulario del usuario
        - Creados o calculados por la aplicación
        - ....

    Son datos que no podemos poner directamente en la consulta SQL

    En su lugar se ponen parámetros y cuando se ejecuta la setencia, se vinculan
    los parámetros con los datos necesarios.

    El proceso es:
        1º Crear la conexión a la BD
        2º Crear una consulta preparada: objeto de la clase mysqli_stmt
           La consulta se crea indicando la sentencia SQL con parámetros.

        3º Se vinculan los valores de los parámetros de la consulta.
           Estos valores tienen que estar saneados.

        4º Se ejecuta la sentencia

        5º Se obtienen los resultados

        6º Se procesan los resultados

    En la sentencia SQL cada parámetro se indica con ?. Ej:
    SELECT referencia, descripcion, pvp, ...
    WHERE pvp > ? AND categoria = ? AND tipo_iva = ?

    INSERT INTO cliente (nif, nombre, apellidos, ...)
    VALUES (?, ?, ?, ...);

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