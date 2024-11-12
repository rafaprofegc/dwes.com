<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require("Empleado.php");

/* 
// Instanciar un objeto antes de __construct()

$emp1 = new Empleado;   // Antes del constructor

$emp1->nif = "30000001A";
$emp1->nombre = "Manuel";
$emp1->apellidos = "García López";
$emp1->salario = 2000;
*/

// 2º Instanciar un objeto con un constructor
$emp1 = new Empleado("30000002B", "María", "López Martínez", 3000);

inicio_html("POO en PHP", ["/estilos/general.css"]);
echo "<header>POO en PHP</header>";

// 3º Acceso a las propiedades de objeto (variables de instancia)
echo "<h3>Acceso a las propiedades del objeto con el operador -></h3>";
echo "<p>Empleado: {$emp1->nif} - {$emp1->nombre} {$emp1->apellidos} - {$emp1->salario}</p>";

// 4º Las constantes de la clase
echo "<h3>Acceso a las constantes de la clase</h3>";
echo "<p>El % de IRPF es " . Empleado::IRPF . " y de Seguridad Social es " . Empleado::SS . "</p>";
printf("<p>El porcentaje de IRPF es %4.2f y de SS es %4.2f</p>", Empleado::IRPF, Empleado::SS);

// 5º Comparación de objetos
echo "<h3>Comparación de objetos</h3>";
//$emp1 = new Empleado("30000002B", "María", "López Martínez", 3000);
$emp2 = new Empleado("30000002B", "María", "López Martínez", 3000);

if( $emp1 == $emp2 ) {
    echo "<p>Emp1 y Emp2 son iguales</p>";
}
else {
    echo "<p>Emp1 y Emp2 son diferentes</p>";
}

if( $emp1 === $emp2 ) {
    echo "<p>Emp1 y Emp2 referencian al mismo objeto</p>";
}
else {
    echo "<p>Emp1 y Emp2 referencian a objetos diferentes</p>";
}
$emp3 = $emp2;
if( $emp2 === $emp3 ) {
    echo "<p>Emp1 y Emp2 referencian al mismo objeto</p>";
}
else {
    echo "<p>Emp1 y Emp2 referencian a objetos diferentes</p>";
}

// 6º Iterar con las propiedades del objeto
echo "<h3>Iterar con las propiedades del objeto</h3>";
foreach($emp1 as $propiedad => $valor) {
    echo "<p>Propiedad $propiedad y valor $valor</p>";
}

// 7º Métodos del objeto
echo "<h3>Métodos</h3>";
$salario_neto = $emp1->getSalarioNeto();
echo "<p>El salario neto de {$emp1->nombre} es $salario_neto €</p>";

// 8º Tipos de datos en propiedades, parámetros de método y devolución de método
echo "<h3>Tipos de datos</h3>";
$emp4 = new Empleado("30000003C", "Juan", "Gómez García");
$salario_neto = $emp4->getSalarioNeto();
if( $salario_neto ) {
    echo "<p>El salario neto es $salario_neto</p>";
}
else {
    echo "<p>Todavía no se le ha asignado un salario</p>";
}

// 9º Promoción del constructor
echo "<h3>Promoción del constructor</h3>";
$emp5 = new Empleado("30000004D", "Carmen", "Montero Sánchez", 4000);
echo "<p>Empleado: {$emp5->nif} - {$emp5->nombre} {$emp5->apellidos} - {$emp5->salario}</p>";

$emp5 = null;

// 10º Objetos como parámetros
if( $emp1->esIgual($emp2) ) {
    echo "<p>Emp1 y Emp2 son iguales</p>";
}
else {
    echo "<p>Emp1 y Emp2 son diferentes</p>";
}

// 11º Objeto como devolución de un método
echo "<p>Nif: {$emp1->nif}. Salario: {$emp1->salario}</p>";
$emp6 = $emp1->salarioDuplicado();
echo "<p>Nif: {$emp6->nif}. Salario: {$emp6->salario}</p>";

// Puedo instanciar un objeto mediante una variable que
// contiene el nombre de la clase
$clase = "Empleado";
$emp1 = new $clase("30000002B", "María", "López Martínez", 3000);

$propiedad = "nif";
echo "<p>El nif es {$emp1->$propiedad}</p>";

fin_html()

?>