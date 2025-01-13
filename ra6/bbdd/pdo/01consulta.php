<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("PDO con MySQL", ["/estilos/general.css", "/estilos/tablas.css"]);

/*
    PDO
    ---
    
    Para acceder a PDO hay que hacer lo siguiente:
    1º.- Crear una conexión con la BD. Crear una instancia de la clase PDO.

    2º.- Crear una consulta (con o sin parámetros)

    3º.- Vincular los valores de los parámetros de la sentencia, si los hay

    4º.- Ejecutar la consulta

    5º.- Acceder al conjunto de resultados

    Conexión PDO. Necesitamos un DSN (Data Source Name). Además, usuario y clave
    Opcionalmente un conjunto de opciones. 
*/

$dsn = "mysql:host=cpd.iesgrancapitan.org;port=9992;dbname=rlozano;charset=utf8mb4";
$usuario = "rlozano";
$clave = "usuario";
$opciones = [
    PDO::ATTR_ERRMODE               =>  PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES      =>  false
];

try {

    // Creo la conexión con la BD
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);

    // Creo una sentencia. Objeto de la clase PDOStatement
    $stmt = $pdo->query("SELECT nif, nombre, apellidos, email FROM cliente");

    // Ejecuta la sentencia
    if( $stmt->execute() ) {
        echo "<h3>Resultados de la consulta</h3>";
        echo "<p>Número de filas " . $stmt->rowCount() . "</p>";

        echo <<<TABLA
        <table>
            <thead>
                <tr>
                    <th>Nif</th>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
        TABLA;
        /*
        // 1ª Forma de acceder a los resultados
        while( $fila = $stmt->fetch() ) {
            echo "<tr>";
            echo "<td>{$fila['nif']}</td>";
            echo "<td>{$fila['nombre']} {$fila['apellidos']}</td>";
            echo "<td>{$fila['email']}</td>";
            echo "</tr>";
        }
        */
        // 2ª Forma de acceder a los resultados
        $filas = $stmt->fetchAll();
        foreach($filas as $fila ) {
            echo "<tr>";
            echo "<td>{$fila['nif']}</td>";
            echo "<td>{$fila['nombre']} {$fila['apellidos']}</td>";
            echo "<td>{$fila['email']}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        

    }
    else {
        echo "<h3>No se ha ejecutado la consulta</h3>";
    }
}
catch( PDOException $pdoe ) {
    mostrar_error($pdoe);
}
finally {
    $pdo = null;
}
fin_html();
?>