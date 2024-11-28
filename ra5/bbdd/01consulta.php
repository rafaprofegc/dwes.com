<?php
/*
    BBDD en PHP
    -----------

    - Extensión MySQLi -> Solo para BBDD MySQL. Es la librería estándar que usa
      PHP para acceder a una BD MySQL. 

    - Extensión PDO -> Para "cualquier" BBDD. Si uso SQL estándar puedo emplear
      cualquier SGBD (MySQL, Oracle, Microsoft SQL SErver, PostgresSQL, ...). Además,
      puedo migrar la BD de un SGBD a otro solamente cambiando una línea código
      en la aplicación.

    - Cualquier sentencia SQL: 
        - DML: SELECT, INSERT, UPDATE, DELETE, 
        - DDL: CREATE TABLE, CREATE VIEW, ...
        - DCL: CREATE USER, ALTER USER, GRANT, REVOKE, ...

    - Pasos para acceder a una BBDD:

        1º Establecer la conexión -> Crear un canal de comunicación entre la aplicación
           y el SGBD. Por ese canal la aplicación (cliente BBDD) envía sentencias SQL
           y el SGBD devuelve el resultado

           Se necesita: servidor (dirección IP o nombre DNS), puerto, usuario y clave. 
           Opcionalmente: El esquema, o la base de datos, a la que se conecta el usuario.

        2º Ejecutar una consulta: Una consulta simple o una consulta preparada. 

        3º Recoger los resultados: Si es SELECT un ResultSet. Si es INSERT, DELETE o UPDATE
           el número de filas afectadas por la sentencia

        4º Si hay un error, lo más habitual de sintaxis SQL, se levanta una excepción
           mysqli_sql_exception

*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Consultas simples con MySQLi", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>Consultas simples a la BBDD</header>";

// Parámetros para abrir conexión con la base de datos
$servidor_local = "localhost";
$servidor_clase = "192.168.12.71";
$servidor_casa = "cpd.iesgrancapitan.org";

$puerto_local = 3306;
$puerto_clase = 3306;
$puerto_casa = 9992;

$usuario_local = "usuario";
$usuario_clase = "rlozano";
$usuario_casa = "rlozano";

$clave = "usuario";

$esquema_local = "tiendaol";
$esquema_clase = $esquema_casa = "rlozano";

try {
    // Crear la conexión con la BBDD
    //$cbd = new mysqli($servidor_local, $usuario_local, $clave, $esquema_local, $puerto_local );
    $cbd = new mysqli($servidor_clase, $usuario_clase, $clave, $esquema_clase, $puerto_clase );

    $controlador = new mysqli_driver();
    $controlador->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;

    // Ejecutar una consulta
    $sql = "SELECT nif, nombre, apellidos, email, iban, telefono, ventas ";
    $sql.= "FROM cliente";

    // Ejecutamos la sentencia en la BBDD y devuelve un ResultSet
    // El resultset es un objeto de la clase mysqli_result
    $resultset = $cbd->query($sql); 

    // Recuperamos los datos del resultset y lo enviamos a la salida
    /* 1ª Forma: Copiamos todas las filas en un array escalar/asociativo
    $filas = $resultset->fetch_all(MYSQLI_ASSOC);
    echo "<table><thead><tr><th>Nif</th><th>Nombre</th><th>Email</th><th>IBAN</th><th>Tlf</th><th>Ventas</th>";
    echo "<tbody>";
    foreach($filas as $fila ) {
        echo "<tr>";
        echo "<td>{$fila['nif']}</td>\n";
        echo "<td>{$fila['nombre']} {$fila['apellidos']}</td>\n";
        echo "<td>{$fila['email']}</td>\n";
        echo "<td>{$fila['iban']}</td>\n";
        echo "<td>{$fila['telefono']}</td>\n";
        echo "<td>{$fila['ventas']}€</td>\n";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "<p>Número de filas: {$resultset->num_rows}</p>";  
    */

    // 2ª Forma: Acceder a los datos fila a fila
    echo "<table><thead><tr><th>Nif</th><th>Nombre</th><th>Email</th><th>IBAN</th><th>Tlf</th><th>Ventas</th>";
    echo "<tbody>";
    while( $fila = $resultset->fetch_assoc() ) {
        echo "<tr>";
        echo "<td>{$fila['nif']}</td>\n";
        echo "<td>{$fila['nombre']} {$fila['apellidos']}</td>\n";
        echo "<td>{$fila['email']}</td>\n";
        echo "<td>{$fila['iban']}</td>\n";
        echo "<td>{$fila['telefono']}</td>\n";
        echo "<td>{$fila['ventas']}€</td>\n";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "<p>Número de filas: {$resultset->num_rows}</p>";  

    // Libero los recursos del resultset
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