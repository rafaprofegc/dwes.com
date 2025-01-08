<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
/*
    Funciones útiles para obtener información de clases
    ---------------------------------------------------

    - is_object($var)   -> True si $var es un objeto
    - gettype($var)     -> Si $var es un objeto devuelve object
    - get_class($var)   -> Devuelve la clase de $var

    - El nombre de la clase lo puede obtener:
        - $objeto::class
        - self::class   -> Dentro de la clase, en uno de sus métodos
        - __CLASS__     -> Constante mágica
        - Clase::class
        - get_class($objeto)

    - property_exists($clase | $objeto, $propiedad) -> True si $propiedad es una
                                                       propiedad de la clase $clase o
                                                       del objeto $objeto. 
    - method_exists($clase | $objeto, $metodo) -> True si $metodo es un método de la
                                                  clase $clase o del objeto $objeto.
                                        
    - Operador instanceof

        $objeto instanceof $clase   -> True si $objeto es una instancia de $clase
    
*/
require_once("06Piso.php");
require_once("Emp.php");

inicio_html("Información de clases", ["/estilos/general.css"]);

$p = new Piso("aaa000", "C/ Mayor, 3", 80, 3, "A");

echo "<header>Información de clases</header>";
echo "<p>Es \$p un objeto: " . (is_object($p) ? "Si" : "No") . "</p>";

$numero = 3;
echo "<p>Es \$numero un objeto: " . (is_object($numero) ? "Si" : "No") . "</p>";

echo "<p>El tipo de \$p es " . gettype($p) . "</p>";

if( is_object($p) ) {
    echo "<p>La clase de \$p es " . get_class($p) . "</p>";
}

if( property_exists($p::class, "planta") && 
    property_exists($p, "planta") && 
    property_exists(Piso::class, "planta") ) {
    echo "<p>La planta es una propiedad de piso</p>";
}
else {
    echo "<p>La planta NO es propiedad de piso</p>";
}

if( method_exists($p::class, "getValorEstimado") &&
    method_exists($p, "getValorEstimado") &&
    method_exists(Piso::class, "getValorEstimado") ) {
    echo "<p>El método getValorEstimado es de " . $p::class . "</p>";
}
else {
    echo "<p>El método getValorEstimado NO es de " . $p::class . "</p>";
}

// Operador instanceof

if( $p instanceof Piso ) {
    echo "<p>\$p es instancia de Piso</p>";
}

if( $p instanceof Vivienda ) {
    echo "<p>\$p es instancia de Vivienda</p>";
}

$emp = new Emp("30000001A", password_hash("usuario", PASSWORD_DEFAULT), "Pepe");

if( $emp instanceof GestionSeguridad ) {
    echo "<p>\$emp es una instancia de GestionSeguridad</p>";
}
else {
    echo "<p>\$emp NO es instancia de GestionSeguridad</p>";
}

fin_html();


?>