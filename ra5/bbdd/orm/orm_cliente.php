<?php
require_once("util/Autocarga.php");

use orm\util\Html;
use orm\util\Autocarga;

Autocarga::registro_autocarga();

Html::inicio("ORM en PHP", ["/estilos/general.css", "/estilos/tablas.css"]);
echo "<header>ORM en PHP</header>";
Html::fin();
?>