<?php
define("PI",3.14159);
define("SALTO","<br>");
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width;initial-scale=1'>
        <title>Variables</title>
    </head>
    <body>
    <h1>Expresiones, operadores y operandos</h1>
    <p>Una expresión es una combinación de operandos y operadores que arroja un
        resultado. Tiene tipo de datos, que depende del tipo de datos de sus
        operandos y de la operación realizada.<br>
        Un operador es un símbolo formado por uno, dos o tres caracteres que denota
        una operación.<br>
        Los operadores pueden ser:<br>
        - Unarios. Solo necesitan un operando.
        - Binarios. Utilizan dos operandos.
        - Ternarios. Utilizan tres operandos.
        Un operando es una expresión en si misma,siendo la más simple un literal o
        una variable, pero también puede ser un valor devuelto por una función o
        el resultado de otra expresión.<br>
        Las operaciones de una expresión no se ejecutan a la vez, sino en orden según
        la precedencia y asociatividad de los operadores. Esta puede alterar a 
        conveniencia. 
    </p>
    <h2>Operadores</h2>
    <h3>Asignación</h3>
<?php
    // El operador de asignación es =
    $numero = 45;
    $resultado = $numero + 5 - 29;
    $sin_valor = NULL;


?>
<h3>Operadores aritméticos</h3>
<?php
    /*  + Suma
        - Resta
        * Multiplicación
        / División
        % Módulo
        ** Exponenciación

        Unarios
        + Conversión a entero
        - El opuesto
    */
    $numero1 = 15;
    $numero2 = 18;
    $suma = $numero1 + 10;
    $resta = 25 - $numero2;
    $opuesto = -$numero1;
    $multiplicacion = $numero1 * 3;
    $division = $numero2 / 3;
    $modulo = $numero1 % 4;
    $potencia = $numero1 ** 2;
    echo "$numero1 y $numero2" . SALTO;
    echo "$suma $resta $opuesto $multiplicacion $division $modulo $potencia" . SALTO;

    $numero3 = "35";
    $numero4 = +$numero3;
    echo "El \$numero4 es $numero4 y su tipo es " . gettype($numero4) . SALTO;

    // No lo hace con float
    $numero5 = PI;
    $numero6 = +$numero5;
    echo "El \$numero6 es $numero6 y su tipo es " . gettype($numero6) . SALTO;

    $numero1 = 35;
    $numero2 = 15;
    $resultado_entero = (int)($numero1 / $numero2);
    echo "El resultado entero es $resultado_entero" . SALTO;  
?>
    <h2>Asignación aumentada</h2>
<?php
    /* Operadores de asignación aumentada
    ++ Incremento
    -- Decremento
    += 
    -=
    *=
    /=
    %=
    */


    $numero = 4;
    $numero++;      // Equivalente a $numero = $numero + 1
    echo "Antes número era 4 ahora es $numero<br>";
    ++$numero;      // Equivalente a $numero = $numero + 1;
    echo "Antes número era 5 ahora es $numero<br>";

    $numero = 10;
    $resultado = $numero++ * 2; // Equivale a $resultado = $numero * 2; $numero = $numero + 1;
    echo "El resultado es $resultado y el numero es $numero<br>";

    $numero = 10;
    $resultado = ++$numero * 2; //Equivale a $numero = $numero + 1; $resultado = $numero * 2;
    echo "El resultado es $resultado y el numero es $numero<br>";

    $numero+=5; //Equivale a $numero = $numero + 5;
    echo "El número es $numero<br>";
    $numero-=3; // Equivale a $numero = $numero - 3;
    echo "El número es $numero<br>";

    $numero *= 3; // Equivale a $numero = $numero * 3;
    echo "El número es $numero<br>";

    $numero %= 7; // Equivale a $numero = $numero % 7;
    echo "El número es $numero<br>";
?>
    <h2>Operadores relacionales</h2>
<?php
    /*
        ==  Igual a
        === Idéntico a
        !=  Distinto
        !== Distinto valor o distinto tipo
        >   Mayor que
        <   Menor que
        >=  Mayor o igual 
        <=  Menor o igual
        <=> Nave espacial
    */
    $n1 = 5;
    $cadena = "5";
    $n2 = 8;

    $resultado = $n1 == $n2;
    echo "Es n1 igual que n2: " . (int)$resultado . "<br>";
    $resultado = $n1 == $cadena;
    echo "Es n1 igual que cadena: " . (int)$resultado . "<br>";
    // Operador ===. Es True si los valores de los operandos son iguales y del mismo tipo.
    $resultado = $n1 === $cadena;
    echo "Es n1 idéntico a cadena: " . (int)$resultado . "<br>";

    $resultado = $n1 != $n2;
    echo "Es n1 distinto de n2: " . (int)$resultado . "<br>";

    // Operador !== . Es True si son distintos o de diferente tipo, false en caso contrario
    $resultado = $n1 != $cadena;
    echo "Es n1 distinto de cadena: " . (int)$resultado . "<br>";
    $resultado = $n1 !== $cadena;
    echo "Es n1 distinto de cadena: " . (int)$resultado . "<br>";

    // Nave espacial
    // Si n1 es mayor que n2 -> 1
    // Si n1 es igual que n2 -> 0
    // Si n1 es menor que n2 -> -1
    // Se emplea para evitar esto:
    // if( $n1 < $n2 ) {
    //
    // elsif ($n1 == $n2)
    //
    // else {
    // 
    // }
    $resultado = $n1 <=> $n2;
    echo "Es n1 menor, igual o mayor que n2: $resultado<br>";

    $nombre1 = "abcZacarias";
    $nombre2 = "abcadela";
    $resultado = $nombre1 > $nombre2;
    echo "El resultado es " . (int)$resultado . "<br>";

    $nombre1 = "MariO";
    $nombre2 = "Maria";
    $resultado = $nombre1 < $nombre2;
    echo "El resultado es " . (int)$resultado . "<br>";

    $nombre1 = "maria";
    $nombre2 = "Maria";
    $resultado = $nombre1 === strtolower($nombre2);
    echo "El resultado es " . (int)$resultado . "<br>";
?>
    <h2>Operadores lógicos</h2>
<?php
    // AND          And lógico o conjunción lógica
    // OR           Or lógico o disyunción lógica
    // XOR          Or exclusivo
    // !            Not
    // &&           And lógico
    // ||           Or lógico
    
    $n1 = 9;
    $n2 = 5;
    $n3 = 10;
    $resultado = $n1 == $n2 or $n2 > $n3;
    $resultado = $n1 == $n2 and $n2 < $n3;

    echo "1.- El resultado es: " . (int)$resultado . "<br>";

    $resultado = $n1 == 9 OR $n2 < $n1 AND $n3 > 10;
    echo "2.- El resultado es: " . (int)$resultado . "<br>";

    $resultado1 = $n1 == 9 OR $n2 < $n1 AND $n3 > 10;
    echo "3.- El resultado es: " . (int)$resultado . "<br>";

    $resultado = ($n1 == 9 OR $n2 < $n1) && $n3 > 10;
    echo "4.- El resultado es: " . (int)$resultado . "<br>";

    $resultado = ($n1 == 9 OR $n2 < $n1) AND $n3 > 10;
    echo "5.- El resultado es: " . (int)$resultado . "<br>";
/*
    $resultado1 = ($n1 == 9 or $n2 < $n1) ? "True" : "False";
    $resultado = ($resultado1 and $n3 > 10) ? "True" : "False";

    echo "3.- El resultado es -> : " . $resultado . " y " . $resultado1 . "<br>";


    $n1 = 9;
    $n2 = 5;
    $n3 = 10;
    $resultado = $n1 == 9 || $n2 < $n1 and $n3 > 10;
    echo "4.- El resultado es: " . (int)$resultado . "<br>";


    $resultado = $n1 + 5 / $n3 < $n1 ** 3 and $n3 / 5 + $n2 * 2 >= $n1 * $n2 / $n3 or 
                    $n1 - 3 % 2 == $n3 - 7;
    echo "El resultado de la expresión grande es: " . (int)$resultado . "<br>";                    
*/

?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

    </body>
</html> 