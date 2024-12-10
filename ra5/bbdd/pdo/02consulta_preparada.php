<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

$dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
$usuario = "rlozano";
$clave = "usuario";
$opciones = [
    PDO::ATTR_ERRMODE               =>  PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES      => false
];

inicio_html("PDO en MySQL", ["/estilos/general.css", "/estilos/tablas.css"]);

try {

    // Crear la conexión con la BD
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);

    // Monto la sentencia con los parámetros
    /* 1ª forma de parámetros: usar ? en cada uno
    $sql = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
    $sql.= "FROM articulo ";
    $sql.= "WHERE pvp > ? AND categoria = ? AND und_vendidas > ?";
    */
    // 2ª forma de parámetros: usar un nombre en cada uno
    $sql = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
    $sql.= "FROM articulo ";
    $sql.= "WHERE pvp > :pvp AND categoria = :categoria AND und_vendidas > :uv";

    // Creo la sentencia preparada (Objeto de la clase PDOStatement)
    $stmt = $pdo->prepare($sql);

    /* Asignar los valores de los parámetros: Método bindParam()
       - Asigna el valor de la variable cuando se ejecute la sentencia.
       - Obligatoriamente tienen que ser variables.
       - Se invoca tantas veces como parámetros haya, una por parámetro
       - Si el parámetro viene en la forma ?, se indica el número de orden y la variable
    */

    $pvp = 7.5;
    $categoria = "TV";
    $und_vendidas = 2;
    // Si los parámetros son ?
    // $stmt->bindParam(1, $pvp);
    // $stmt->bindParam(2, $categoria);
    // $stmt->bindParam(3, $und_vendidas);

    // Si los parámetros son nombres
    // $stmt->bindParam(':pvp', $pvp);
    // $stmt->bindParam(':categoria', $categoria);
    // $stmt->bindParam(':uv', $und_vendidas);
    
    /* Asignar los valores de los parámetros: Método bindValue()
        - Asigna el valor de una expresión cuando se ejecuta el método
        - Puede ser una expresión
        - Se invoca tantas veces como parámetros haya, una por parámetro
        - Si el parámetro viene en la forma ?, se indica el número de orden y la expresión
    
    $pvp = 7.5;
    $stmt->bindValue(1, $pvp - 5.25);
    $stmt->bindValue(2, 'CONF');
    $stmt->bindValue(3, 20);
    */

    // En el método execute() puedo asignar los parámetros
    $parametros = [':pvp'       =>  2.25,
                   ':categoria' => 'CONF',
                   ':uv'        => 20
                  ];

    if( $stmt->execute($parametros) ) {
        echo "<h3>Listado de artículos</h3>";
        echo "<p>Número de filas: " . $stmt->rowCount() . "</p>";
        echo <<<TABLA
            <table>
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Descripción</th>
                        <th>PVP</th>
                        <th>Categoría</th>
                        <th>Und Vend</th>
                    </tr> 
                </thead>
                <tbody>
        TABLA;
        while( $fila = $stmt->fetch() ) {
            echo "<tr>";
            echo "<td>{$fila['referencia']}</td>";
            echo "<td>{$fila['descripcion']}</td>";
            echo "<td>{$fila['pvp']}</td>";
            echo "<td>{$fila['categoria']}</td>";
            echo "<td>{$fila['und_vendidas']}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    else {
        echo "<h3>Error en la consulta</h3>";
    }
}
catch( PDOException $pdoe) {
    mostrar_error($pdoe);
}
finally {
    $pdo = null;
}

?>