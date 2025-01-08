<?php
require_once("util/Autocarga.php");

use orm\util\Html;
use orm\util\Autocarga;
use orm\entidad\Articulo;
use orm\modelo\ORMArticulo;
use orm\entidad\Entidad;

Autocarga::registro_autocarga();

Html::inicio("ORM en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>ORM en PHP</header>";

$orm_articulo = new ORMArticulo();
$articulo = $orm_articulo->get("ACIN0003");
echo "<h3>Artículo con referencia ACIN0003</h3>";
echo "$articulo";

echo "<h3>Listado de todos los artículos</h3>";
$articulos = $orm_articulo->getAll();
echo <<<TABLA
    <table>
        <thead>
        <tr>
            <th>Referencia</th>
            <th>Descripción</th>
            <th>PVP</th>
            <th>Dto. Venta</th>
            <th>Und. Vendidas</th>
            <th>Und. Disponibles</th>
            <th>Fecha disponible</th>
            <th>Categoría</th>
            <th>Tipo IVA</th> 
        </tr>
        </thead>
        <tbody>
TABLA;
foreach($articulos as $articulo ) {
    echo "<tr>";
    echo "<td>{$articulo->referencia}</td>";
    echo "<td>{$articulo->descripcion}</td>";
    echo "<td>{$articulo->pvp}</td>";
    echo "<td>" . ($articulo->dto_venta * 100) . "%</td>";
    echo "<td>{$articulo->und_vendidas}</td>";
    echo "<td>{$articulo->und_disponibles}</td>";
    echo "<td>" . $articulo->fecha_disponible->format(Entidad::FECHA_HORA_USUARIO) . "</td>";
    echo "<td>{$articulo->categoria}</td>";
    echo "<td>" . Articulo::$TIPOS_IVA[$articulo->tipo_iva] . "</td>";
    echo "</tr>";
}
echo <<<FIN_TABLA
    </tbody>
</table>
FIN_TABLA;

echo "<p>Número de artículos: " . count($articulos) . "</p>";

echo "<h3>Insertamos un artículo nuevo</h3>";
$articulo = new Articulo(['referencia'          => 'ACIN0011',
                        'descripcion'           => 'Hub USB C',
                        'pvp'                   => 40.5,
                        'dto_venta'             => 0.15,
                        'und_vendidas'          => 10,
                        'und_disponibles'       => null,
                        'fecha_disponible'      => new DateTime("13.02.2025"),
                        'categoria'             => 'ACIN',
                        'tipo_iva'              => 'N']);

if( $orm_articulo->insert($articulo) ) {
    echo "<h4>El artículo recién insertado</h4>";
    $nuevo_articulo = $orm_articulo->get($articulo->referencia);
    echo "$nuevo_articulo";
}
else {
    echo "<h4>Error al insertar un nuevo artículo</h4>";
}

echo "<h3>Modificamos el artículo recién insertado</h3>";
$nuevo_articulo->descripcion = "Hub USB C 2USBc 1 USBa 1 HDMI 1 VGA 1 RJ45";
$nuevo_articulo->pvp = "50.75";
$nuevo_articulo->fecha_disponible = "2025-03-03 17:15:50";

if( $orm_articulo->update($nuevo_articulo->referencia, $nuevo_articulo) ) {
    echo "<h4>El artículo recién modificado</h4>";
    $articulo_modificado = $orm_articulo->get($nuevo_articulo->referencia);
    echo "$articulo_modificado";
}
else {
    echo "<h4>Error al modificar un nuevo artículo</h4>";
}

echo "<h3>Borramos el artículo recién insertado y modificado</h3>";
if( $orm_articulo->delete($articulo_modificado->referencia)) {
    echo "<h4>El artículo con referencia {$articulo_modificado->referencia} se ha borrado</h4>";
}
else {
    echo "<h4>Error al eliminar el artículo</h4>";
}

Html::fin();
?>