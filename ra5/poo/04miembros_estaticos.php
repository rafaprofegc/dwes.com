<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("Empleado.php");

date_default_timezone_set("Europe/Madrid");

inicio_html("Miembros estáticos", ["/estilos/general.css"]);
echo "<header>Miembros estáticos de una clase</header>";
echo "<h3>Accedo a propiedades estáticas de Empleado</h3>";
echo "<p>La retención por IRPF es " . (Empleado::$IRPF * 100) . " %</p>";

$emp1 = new Empleado("30000001A", "Manuel", "García Gómez", 2000);
$emp2 = new Empleado("30000002B", "María", "López González", 2000);

echo "<p>La retención por IRPF de {$emp1->nombre} es {$emp1::$IRPF} %</p>";

$emp1::$IRPF = 0.3;

echo "<p>La retención por IRPF de {$emp2->nombre} es {$emp2::$IRPF} %</p>";

echo Empleado::getPorcentajes() . "<br>";

$dentro_de_un_mes = time() + 30 * 24 * 60 * 60;
echo Empleado::getFechaFormato($dentro_de_un_mes) . "<br>";
fin_html();


?>