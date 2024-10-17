<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Estructuras de control</title>
    </head>
    <body>
        <h1>Estructuras de control</h1>
 
        <h2>Sentencias</h2>
        <p>La sentencias simples acaban en ;, pudiendo haber más de una
            en la misma línea</p>
<?php
    $numero = 3; echo "El numero es $numero<br>"; $numero += 3; print "Ahora es $numero<br>";        
?>
    <p>Un bloque de sentencias es un conjunto de sentencias encerrados entre llaves. No
        suelen ir solas, sino formar parte de una estructura de control. Además, se pueden
        anidar.</p>
<?php
    {
        $numero = 5;
        echo "El número es $numero<br>";
        $numero -= 2;
        echo "Ahora es $numero<br>";
        {
            $numero = 8;
            $numero *= 2;
            echo "El resultado es $numero<br>";
        }
        echo "El número es $numero<br>";
    }
?>
    <h2>Estructuras condicional simple</h2>
<?php
    // if( expresión ) sentencia;
    $numero = 4;
    if( $numero >= 4 ) echo "El número es mayor o igual a 4<br>";

    if( $numero === 4 and $numero % 2 == 0 ) 
        echo "El número es igual a 4 y su resto al dividir por 2 es 0<br>";
?>
    <h2>Estructura de condicional compuesta</h2>

<?php
    $n1 = 9;
    $n2 = 5;
    $n3 = 10;
    if ( ($n1 == 9 OR $n2 < $n1) AND $n3 > 10) {
        echo "El resultado global es True<br>";
    }
    else {
        echo "El resultado global es False<br>";
    }

    if( $n1 > 20 Or $n3 < 15 ) echo "La condición es True<br>";
    else echo "La condición es False<br>";

    $edad = 21;
    if( $edad > 18 )
        echo "Puedes ver la peli<br>";
    else
        echo "No puedes ver la peli para mayores<br>";

    $tipo_carnet = "C1";
    if( $edad > 21 and $tipo_carnet == "C1") {
        echo "Obtención del carnet de camión<br>";
        echo "Tienes $edad años y al superar los 21 puedes obtener el carnet $tipo_carnet<br>";
    }
    else {
        echo "No cumples los requisitos para el carnet $tipo_carnet<br>";
        echo "Comprueba que tienes más de 21 años<br>";
    }

    // Uso de código HTML en las estructuras de control
    if( $edad > 18 And $edad < 65 ) { ?>
        <h3>Servicios del gimnasio disponibles para la edad <?= $edad?>:</h3>
        <ul>
            <li>Spinning</li>
            <li>Boxeo</li>
            <li>Zumba</li>
        </ul>
<?php
    }
    else { ?>
        <h3>Servicios para jubilados o menores de 18</h3>
        <ul>
            <li>Taichi</li>
            <li>Pilates</li>
            <li>Yoga</li>
        </ul>
<?php
    }

    if( $tipo_carnet == "C1" )
        echo <<<CARNET_C1
    <h2>Documentación para obtener el carnet $tipo_carnet</h2>
    <ul>
        <li>Fotocopia del carnet</li>
        <li>Certificado de penales</li>
        <li>Carnet B2</li>
    </ul>
    CARNET_C1;

?>
    <h2>If else anidado</h2>
<?php
    $nota = 6.5;
    if ($nota >= 0 And $nota < 5 )
        echo "Suspenso";
    else
        if( $nota < 6 )
            echo "Aprobado";
        else
            if( $nota < 7 )
                echo "Bien";
            else
                if( $nota < 9 )
                    echo "Notable";
                else
                    if( $nota <= 10 )
                        echo "Sobresaliente";
                    else
                        echo "El valor de la nota no es correcto";
    echo "<br>";

    if( $nota >= 0 and $nota < 5 ) {
        echo "Suspenso";
    }
    elseif ($nota < 6 ) {
        echo "Aprobado";
    }
    else if ($nota < 7 ) {
        echo "Bien";
    }
    elseif ($nota < 9 ) {
        echo "Notable";
    }
    elseif ($nota <= 10 ) {
        echo "Sobresaliente";
    }
    else {
        echo "La nota no es correcta";
    }
    echo "<br>";
?>
    <h2>Estructura condicional múltiple</h2>
<?php
    // Estructura condicional múltiples switch
    $nota = 7;
    switch( $nota ) {
        case 1: 
        case 2:
        case 3:
        case 4:
            echo "Suspenso";
            break;
        case 5:
            echo "Aprobado";
            break;
        case 6:
            echo "Bien";
            break;
        case 7:
        case 8:
            echo "Notable";
            break;
        case 9:
        case 19:
            echo "Sobresaliente";
            break;
        default:
            echo "La nota no es correcta";
    }
?>
    <h2>Expresión match</h2>
<?php
    // Expresión match
    $calificacion = match($nota) {
        0, 1, 2, 3, 4               => "Suspenso",
        "5"                         => "Suspenso con cadena",
        5                           => "Aprobado",
        6                           => "Bien",
        7, 8                        => "Notable",
        9, 10                       => "Sobresaliente",
        default                     => "Nota errónea"
    };

    echo "Con tu nota $nota tienes una calificación de $calificacion<br>";
?>
    <h2>Operador ternario ?</h2>
<?php
    // Sintaxis:
    //  <condición> ? <expresión_true> : <expresión_false>;

    $nota = 6;
    $resultado = $nota >= 5 ? "Con un $nota estás Aprobado" : "Con un $nota suspenso";
    echo "$resultado<br>";

    $con_beca = !True;
    $nombre = "Juan Gómez";
?>
    
    <form action="" method="POST">
        ...
        <input type="text" name="nombre" size="30" value="<?= isset($nombre) ? $nombre : ""?>"><br>
        <input type="checkbox" name="con_beca" <?= $con_beca ? "checked" : "" ?>>Con beca<br>
       <button>Enviar</button> 
    </form>

    <h2>Operador de fusión NULL</h2>
<?php
    $metodo = "POST";
    $segundo_metodo = "GET";
    $por_defecto = "main";

    $resultado = $metodo ?? $segundo_metodo;

    echo "El resultado es $resultado<br>";

    unset($metodo);
    $segundo_metodo = "GET";
    $por_defecto = "main";

    $resultado = $metodo ?? $segundo_metodo;

    echo "El resultado es $resultado<br>";

    unset($metodo);
    unset($segundo_metodo);
    $por_defecto = "main";

    $resultado = $metodo ?? $segundo_metodo ?? $por_defecto;

    echo "El resultado es $resultado<br>";

?>
    <h2>Bucles</h2>
    <ul>
        <li>For con contador (estilo Java y C++)</li>
        <li>For para colecciones de datos</li>
        <li>While</li>
        <li>Do .. while</li>
        <li>Sentencias break y continue</li>
    </ul>
    <h3>Bucle for con contador</h3>
<?php
    // Tabla de multiplicar del 4
    $numero = 4;
    echo "La tabla de multiplicar del 4<br>";
    for( $i = 1; $i <= 10; $i++) {
        echo "$numero x $i = " . strval($numero * $i) . "<br>";
    }

    // Diferencias entre:
    // $i++, ++$i   -> Ninguna

    echo "Los 10 primeros números pares<br>";
    for( $i = 2; $i <= 2 * 10; $i+=2 ) {
        echo "Número par $i<br>";
    }

    echo "La cuenta atrás para el lanzamiento<br>";
    for( $i = 10; $i >= 0; $i-- ) {
        echo "$i segundos<br>";
    }
    echo "Ignición<br>";

    // Varias expresiones en el inicio del contador
    // y en la parte del incremento
    for( $i = 10, $j = 0; $i >= 5 And $j < 8; $i--, $j+=2 ) {
        echo "Valor de i es $i y valor de j es $j<br>";
    }
?>
    <h3>Bucle while</h3>
<?php
    // Sumar los números pares que se generan aleatoriamente
    // hasta que salga el 0
    // while (condición) sentencia;

    $numero = rand(0,10);
    $total = 0;
    while ( $numero != 0 ) {
        echo "El número generado es $numero<br>";
        if ( $numero % 2 == 0 ) 
            $total += $numero;

        $numero = rand(0,10);
    }

    echo "El total de los pares es $total<br>";

?>
    <h3>Do .. While</h3>
<?php
    // Contar cuantos números impares
    // se generan aleatoriamente
    // hasta que se genera uno negativo
    $total = 0;
    do {
        $numero = rand(-5, 50);
        echo "Se ha general el $numero<br>";
        if( abs($numero) % 2 == 1 ) 
            $total++; 

    }
    while( $numero >= 0 );
    echo "Se han generado $total números impares";

?>
    <h3>Sentencias break y continue</h3>
<?php
    // Bucle repetir .. hasta con break
    $total = 0;
    do {
        $numero = rand(0,20);
        if( $numero % 3 == 0 && $numero ) {
            $total++;
            echo "<span style='color:red;'>El número $numero es múltiplo de 3</span><br>";
        }
        echo "El número generado es $numero<br>";
        if( !$numero ) break;
    }
    while(True);

    echo "Se han generado $total números múltiplos de 3<br>";

    // Generar números aleatorios entre 1 y 10, y sumar los pares
    // hasta que la suma sea superior a 100 o se hayan generado
    // como máximo 20 números.

    $suma_pares = 0;
    $contador = 0;
    while( True ) {
        $numero = rand(1,10);
        if( $numero % 2 == 0 )
            $suma_pares += $numero;

        if( $suma_pares > 100 ) break;

        $contador++;
        if( $contador == 20 ) break;
    }
    echo "Se han generado $contador números y la suma de los pares es $suma_pares<br>";

    // Break admite un argumento numérico entero para indicar
    // de qué bucle se sale.
    // Solo sirve si hay bucles anidados.

    // Generar 200 números aleatorios entre 1 y 1000
    // Por cada número se comprueba cuantos números primos
    // hay desde 1 hasta ese número.
    // Si hay más de 10 números primos que termine.
    // Al final visualizar cada número generado y los primos hasta ese número
    
    /*
    Ej: Se genera aleatoriamente el 25:
        El número es 25 y los primos hasta el 25 son: 1, 2, 3, 5, 7, 11, 13, 17, ...
    */

    for( $i = 0; $i < 200; $i++ ) {
        $numero = rand(1,1000);
        echo "El número generado es: $numero. Primos: ";
        $cuantos_primos = 0;
        for( $j = 1; $j < $numero; $j++ ) {
            // Averiguar si $j es primo
            $es_primo = True;
            $raiz_cuadrada = $j ** 0.5; // $raiz_cuadrada = sqrt($j);
            $k = 2;
            while( $es_primo && $k <= $raiz_cuadrada ) {
                if( $j % $k == 0 ) $es_primo = False;
                $k++;
            }
            // ¿Cómo podemos saber si el número $j es primo
            if ( $es_primo ) {
                echo "$j ";
                $cuantos_primos++;

                if( $cuantos_primos > 10 ) break 2;
            }
        }
    }

    // Genera 10 números aleatorios
    // Por cada uno genera tantos caracteres en minúscula aleatorios como ese número
    // Si alguno de los caracteres generados es z, se acaba y
    // no se generan 
    // Si el número generado es impar, que vuelva a generar otro.

    for( $i = 0; $i < 10; $i++ ) {
        $numero = rand(1,10);
        echo "<br>Número: $numero ";
        if ( $numero % 2 == 1 ) continue;

        for( $j = 1; $j <= $numero; $j++ ) {
            // Genero un carácter aleatorio
            $codigo_ascii_letra = rand(97, 122);
            $caracter = chr($codigo_ascii_letra);
            echo "$caracter ";
            if( $caracter == "z" ) break 2;
        }
        echo "<br>";
    }

?>
    <h3>Sintaxis alternativa a la estructuras de control</h3>
<?php
    $numero = rand(1,100);
    if( $numero % 2 == 0 ):
        echo "El número $numero es par<br>";
    else:
        echo "El número $numero es impar<br>";
    endif;

    for($i = 1; $i <= 10; $i++ ):
        echo "$i x $numero = " . $i * $numero . "<br>";
    endfor;

    $i = 10;
    while( $i > 0 ):
        echo "El valor de i es $i<br>";
        $i--;
    endwhile;
?>
<br>
<br>
<br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br>



    </body>
</html>