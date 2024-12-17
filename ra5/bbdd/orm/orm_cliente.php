<?php
require_once("util/Autocarga.php");

use orm\util\Html;
use orm\util\Autocarga;
use orm\entidad\Cliente;
use orm\modelo\ORMCliente;

Autocarga::registro_autocarga();

Html::inicio("ORM en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>ORM en PHP</header>";

/*
$cliente = new Cliente(['nif'=> '40000001A',
                        'nombre' => 'María',
                        'apellidos' => 'González',
                        'clave' => password_hash("usuario", PASSWORD_DEFAULT),
                        'iban'  => 'ES84',
                        'telefono' => '600000000',
                        'email' => 'mariadelao@loquesea.com',
                        'ventas' => 0]);
echo "$cliente";                        
*/
$orm_cliente = new ORMCliente();
$nif = "30000001A";
$cliente = $orm_cliente->get($nif);
echo "<h3>Obtenemos el cliente con nif = $nif</h3>";
echo "$cliente";

echo "<h3>Listado de todos los clientes</h3>";
$clientes = $orm_cliente->getAll();
echo <<<TABLA
    <table>
        <thead>
        <tr>
            <th>Nif</th>
            <th>Nombre</th>
            <th>Iban</th>
            <th>Tlf</th>
            <th>Email</th>
            <th>Ventas</th>
        </tr>
        </thead>
        <tbody>
TABLA;
foreach($clientes as $cliente ) {
    echo "<tr>";
    echo "<td>{$cliente->nif}</td>";
    echo "<td>{$cliente->nombre} {$cliente->apellidos}</td>";
    echo "<td>{$cliente->iban}</td>";
    echo "<td>{$cliente->telefono}</td>";
    echo "<td>{$cliente->email}</td>";
    echo "<td>{$cliente->ventas}</td>";
    echo "</tr>";
}
echo <<<FIN_TABLA
    </tbody>
</table>
FIN_TABLA;

echo "<p>Número de clientes: " . count($clientes) . "</p>";

echo "<h3>Insertamos un cliente nuevo</h3>";
$cliente = new Cliente(['nif'       => '40000002B',
                        'nombre'    => 'Javier',
                        'apellidos' => 'Gómez Martínez',
                        'clave'     => password_hash("usuario", PASSWORD_DEFAULT),
                        'iban'      => 'ES84',
                        'telefono'  => null,
                        'email'     => 'javier@loquesea.com',
                        'ventas'    => 0 ]);
if( $orm_cliente->insert($cliente) ) {
    echo "<h4>El cliente recién insertado</h4>";
    $nuevo_cliente = $orm_cliente->get($cliente->nif);
    echo "$nuevo_cliente";
}
else {
    echo "<h4>Error al insertar un nuevo cliente</h4>";
}

echo "<h3>Modificamos el cliente recién insertado</h3>";
$nuevo_cliente->nombre = "Sara";
$nuevo_cliente->apellidos = "Márquez Sánchez";
$nuevo_cliente->iban = "ES99";
$nuevo_cliente->telefono = "999000999";
$nuevo_cliente->email = "sara@loquesea.com";

if( $orm_cliente->update($nuevo_cliente->nif, $nuevo_cliente) ) {
    echo "<h4>El cliente recién modificado</h4>";
    $cliente_modificado = $orm_cliente->get($nuevo_cliente->nif);
    echo "$cliente_modificado";
}
else {
    echo "<h4>Error al actualizar un nuevo cliente</h4>";
}

echo "<h3>Borramos el cliente recién insertado y modificado</h3>";
if( $orm_cliente->delete($cliente_modificado->nif)) {
    echo "<h4>El cliente con nif $cliente_modificado->nif se ha borrado</h4>";
}
else {
    echo "<h4>Error al eliminar el cliente</h4>";
}

Html::fin();
?>