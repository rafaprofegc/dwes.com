<?php
require_once("06Piso.php");
require_once("06Vivienda.php");
require_once("06Casa.php");
// require_once("06Apartamento.php");
require_once("06Chalet.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");


function ver_valor_estimado(Vivienda $v) :void  {
    $valor_estimado = $v->getValorEstimado(1200);
    echo "<p>Vivienda: $v : Precio estimado: $valor_estimado €</p>";
}

inicio_html("Herencia en PHP", ["/estilos/general.css"]);
echo "<header>Herencia en PHP</header>";
/*
La clase Vivienda es ahora abstracta

$v = new Vivienda("aaa001", "c/ Cruz Conde, 24", 120);
echo "Vivienda: $v<br>";
*/

$p = new Piso("bbb002", "Av. América, 15", 100, 3, "A");
echo "Piso: $p<br>";

echo "El valor del piso es: " . $p->getValorEstimado(1000) . "<br>";

$c = new Casa("ccc003", "Av del Brillante, 20", 200, 150, 100);
echo "Casa: $c<br>";

echo "El valor de la casa es: ". $c->getValorEstimado(1000) . "<br>";

// Despacho de métodos dinámico
// ver_valor_estimado($v);
ver_valor_estimado($p);
ver_valor_estimado($c);

/*
El piso es clase final. NO se puede heredar
$a = new Apartamento("ddd004", "c/ Gongora, 3", 50, 4, "B", 2);
echo "Apartamento $a<br>";
*/

// Creo una instancia a Chalet con un método estático

$ch = Chalet::getChalet("eee005", "Av. Brillante, 20", 110, 50, 20, 30, 1000 );
echo $ch->ref_cat ."<br>";

$ch1 = Chalet::getChalet("eee005", "Av. Brillante, 20", 110, 50, 20, 30, 1000 );
$ch2 = Chalet::getChalet("eee005", "Av. Brillante, 20", 110, 50, 20, 30, 1000 );
$ch3 = Chalet::getChalet("eee005", "Av. Brillante, 20", 110, 50, 20, 30, 1000 );

fin_html();
?>