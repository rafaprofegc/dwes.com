<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("Direccion.php");
require_once("Empleado.php");

inicio_html("Métodos mágicos en PHP", ["/estilos/general.css"]);

echo "<header>Métodos mágicos en PHP</header>";

$dir1 = new Direccion("C/", "Mayor", 3, 2, "A", 4, "B", 28000, "Madrid");

echo "<h3>Sobrecarga de propiedades</h3>";
$dir1->setTipoVia("Av");

echo "<p>{$dir1->tipo_via} $dir1->nombre_via , $dir1->numero</p>";
$dir1->nombre_via = "Cervantes";
echo "<p>{$dir1->tipo_via} $dir1->nombre_via , $dir1->numero</p>";

$dir1->tipo_via = "Calle";
echo "<p>{$dir1->tipo_via} $dir1->nombre_via , $dir1->numero</p>";

$dir1->tipo_via = "Pz";
echo "<p>{$dir1->tipo_via} $dir1->nombre_via , $dir1->numero</p>";

if( isset($dir1->nombre_via) ) {
    echo "<p>El nombre vía está definido</p>";
}

unset($dir->nombre_via);

if( isset($dir1->nombre_via) ) {
    echo "<p>El nombre vía está definido</p>";
}

echo "<p>{$dir1->tipo_via} $dir1->nombre_via , $dir1->numero</p>";

// Objeto como cadena de caracteres
echo "$dir1";


// Clonación de objetos
$num1 = 8;
$num2 = $num1;

echo "<p>Tenemos dos variables número $num1 y $num2</p>";

$dir2 = $dir1;
echo "<p>Tenemos dos referencias al mismo objeto</p>";
$dir2->tipo_via = "Crta";
echo $dir1;

if( $dir1 === $dir2 ) {
    echo "<p>Las dos direcciones son el mismo objeto</p>";
}

// Hacer una copia de un objeto
$dir3 = clone $dir1;
echo $dir3;
if( $dir1 === $dir3 ) {
    echo "<p>Las dos direcciones son el mismo objeto</p>";
}
else  {
    echo "<p>Las direcciones son objetos diferentes</p>";
}

$dir1->tipo_via = "Av";
$dir3->tipo_via = "Ps";

echo "$dir1";
echo "<br>$dir3";

$emp1 = new Empleado("30000001A", "María", "Gómez García", 2000, $dir1);
$emp2 = clone $emp1;
$emp2->nombre = "Javier";

echo "$emp1";
echo "$emp2";

$emp1->direccion->nombre_via = "Arcos de la Frontera";

echo "$emp1";
echo "$emp2";

if( $emp1->direccion === $emp2->direccion ) {
    echo "<p>Los dos empleados tienen el mismo objeto dirección </p>";
}
else {
    echo "<p>Los dos empleados tienen cada uno su propio objeto dirección</p>";
}

// Clone sobre propiedades que son arrays
$emp1->cc = ["123", "456"];
$emp3 = clone $emp1;
$emp3->cc = ["789", "012"];

echo "$emp1";
echo "$emp3";

?>