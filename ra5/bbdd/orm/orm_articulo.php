<?php
require_once("util/Autocarga.php");

use orm\util\Html;
use orm\util\Autocarga;
use orm\entidad\Articulo;
use orm\modelo\ORMArticulo;

Autocarga::registro_autocarga();

Html::inicio("ORM en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>ORM en PHP</header>";

$orm_articulo = new ORMArticulo();
$articulo = $orm_articulo->get("ACIN0003");
echo "<h3>Artículo con referencia ACIN0003</h3>";
echo "$articulo";

Html::fin();
?>