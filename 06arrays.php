<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Array</title>
    </head>
    <body>
        <h1>Array</h1>
        <p>Un array un conjunto elementos que se referencian con el mismo nombre. A
            cada variable del array se le conoce como componente o elemento del array.
            Cada componente tiene asociado una clave que se emplea para acceder a ese
            componente. </p>
        <p>En PHP los arrays son muy flexibles. Hay de dos tipos: escalares y asociativos.
            Para acceder a un elemento se usa su clave con el operador []. Si la clave es
            un número entero mayor o igual que 0 es un array escalar. Si la clave es una
            cadena de caracteres es un array asociativo.</p>

        <h2>Array escalar</h2>
<?php
    // Un array se define de dos formas
    // 1ª con la función Array()
    $notas = Array(4, 9, 7.5, 6, 2.5);
    // 2ª con un literal
    $numeros =  [8, 4, 2, 9, 5.5];

    // Si solo se indican los elementos del array, la clave comienza por 0 desde
    // la izquierda
    // El acceso a los elementos es mediante su clave o índice entre corchetes
    echo "La primera nota es $notas[0]<br>";
    echo "La tercera nota es $notas[2]<br>";

    // Al definir el array podemos indicar los índices
    $notas = Array( 2 => 8.5, 4 => 4.75, 8 => 3.5);

    // Puede definir índice para algunos y no para otros
    $notas = Array( 3 => 5, 6.5, 8, 7 => 2, 9, 5);
    echo "Índice 0: $notas[0]<br>";
    echo "Índice 1: $notas[1]<br>";
    echo "Índice 2: $notas[2]<br>";
    echo "Índice 3: $notas[3]<br>";
    echo "Índice 4: $notas[4]<br>";
    echo "Índice 5: $notas[5]<br>";
    echo "Índice 6: $notas[6]<br>";
    echo "Índice 7: $notas[7]<br>";
    echo "Índice 8: $notas[8]<br>";
    echo "Índice 9: $notas[9]<br>";

    // Borramos el elemento 4
    unset($notas[4]);
    if( isset($notas[4]) ) {
        echo "El elemento 4 está definiod y es $notas[4]<br>";
    }
    else {
        echo "El elemento 4 no está definido<br>";
    }

    // Modificar un elemento del array
    $notas[5] = rand(1,10);
    echo "Índice 5: $notas[5]<br>";

    $notas[] = 7.5;
    echo "He añadido el elemento con índice 10: $notas[10]<br>";
?>
    <h2>Arrays asociativos</h2>
    <p>Array que tiene una cadena de caracteres como clave</p>
<?php
    $coche['1234BBC'] = "Seat León";
    $coche['4321CCB'] = "Ford Focus";

    echo "Mi coche es {$coche['1234BBC']}<br>";
    echo "Tu coche es " . $coche['4321CCB'] . "<br>";   
?>
    <h2>Array mixto</h2>
    <p>Cuando las claves son índices numéricos o cadenas indistintamente</p>
<?php
    $alumno['nombre'] = "Juan Gómez";
    $alumno[0] = 4;
    $alumno[1] = 6;
    $alumno[2] = 5;
    $alumno['media'] = 5;

    echo "El alumno {$alumno['nombre']} y tiene de notas $alumno[0], $alumno[1] y $alumno[2].<br>";
    echo "Su media es {$alumno['media']}<br>";

    $alumno = ['nombre' => "Manuel Martínez", 0 => 3, 8, 5, 4, 'media' => 5];

    echo "El alumno {$alumno['nombre']} y tiene de notas $alumno[0], $alumno[1] y $alumno[2].<br>";
    echo "Su media es {$alumno['media']}<br>";
?>
    <h2>Arrays bidimensionales</h2>
    <p>Arrays con dos dimensiones y por tanto para acceder a un elemento hacen
        falta dos claves</p>
<?php
    $notas = Array(
        Array(3.5, 6, 8, 9.5, 3), 
        Array(2, 5.5, 6, 2, 10), 
        Array(4.5, 3, 2.5, 7, 8), 
        Array(7, 1, 0, 1.5, 3.5)
    );

    echo "El elemento en la fila 2 columna 3 -> {$notas[1][2]}<br>";
    
    $notas[][] = 9;

    echo "El último elemento de la última fila: {$notas[4][0]}<br>";
    $notas[3][] = 7.5;

    echo "El último elemento de la fila 3 (la cuarta): {$notas[3][5]}<br>";

    // Se accede por clave, no por índice.
    $numeros = [-1 => 1, 2,3,4,5];
    echo "El último elemento del array es {$numeros[-1]}<br>";

    $coches = [
        '1234bbc' => ['marca' => 'Seat', 'modelo' => 'Ibiza', 'motor' => 'Diesel', 'pvp' => 18000],
        '4321ccb' => ['marca' => 'Ford', 'modelo' => 'Focus', 'motor' => 'Gasolina', 'pvp' => 21000]
    ];

    echo "El primer coche es {$coches['1234bbc']['marca']} modelo {$coches['1234bbc']['modelo']}<br>";

    // Crea un array de un equipo de fútbol donde cada fila son las posiciones
    // donde juegan los jugadores con el conjunto de jugadores identificados por su
    // dorsal.
?>
    <h2>Arrays multidimensionales</h2>
<?php
    $notas = [
        [
            [3, 4, 5, 6],
            [2, 8, 9, 3]    
        ],
        [
            [1, 9, 8, 5],
            [2, 8, 4, 5]
        ],
            [2, 8, 4, 6],
            [9, 10, 4, 3]
    ];

    echo "Accedo al elemento 1, 1, 2: {$notas[1][1][2]}<br>";

    $notas = [
        'juan' => [
            'T1' => ['dwes' => 6, 'dwec' => 5, 'daw' => 8, 'diw' => 7],
            'T2' => ['dwes' => 5.5, 'dwec' => 7.5, 'daw' => 6, 'diw' => 6],
            'T3' => ['dwes' => 5, 'dwec' => 7, 'daw' => 6.5, 'diw' => 4]
        ],
        'maria' => [
            'T1' => ['dwes' => 9, 'dwec' => 6, 'daw' => 7.5, 'diw' => 7],
            'T2' => ['dwes' => 8, 'dwec' => 7, 'daw' => 6.5, 'diw' => 5.5],
            'T3' => ['dwes' => 7, 'dwec' => 7, 'daw' => 4.5, 'diw' => 5.5]
        ]
    ];

    $alumno = "maria";
    $trimestre = "T2";
    $modulo = "dwec";

    echo "La nota de maria en el segundo trimestre en dwec: {$notas['maria']['T2']['dwec']}<br>";
    echo "La nota de maria en el segundo trimestre en dwec: {$notas[$alumno][$trimestre][$modulo]}<br>";
?>
<h2>Recorrer un array</h2>
<?php
    echo "<h3>Bucle for tradicional</h3>";
    // Para arrays escalares puedo usar un bucle for tradicional
    $numeros = [6, 19, 12, 7, 11, 9, 3];
    for( $i = 0; $i < count($numeros); $i++) {
        echo "Elemento $i es {$numeros[$i]}<br>";
    }

    // Para cualquier tipo de array tenemos el bucle foreach
    // foreach ($array as [$clave =>] $valor) {
    //
    // }
    echo "<h3>Bucle foreach</h3>";
    foreach($numeros as $numero) {
        echo "El número es $numero<br>";
    }

    echo "<br>";
    $alumno = [];   // Crea un array vacío. Equivalente a $alumno = Array();
    $alumno['nombre'] = "Juan Gómez";
    $alumno[0] = 4;
    $alumno[1] = 6;
    $alumno[2] = 5;
    $alumno['media'] = 5;

    foreach( $alumno as $clave => $valor) {
        echo "Elemento con clave $clave y valor $valor<br>";
    }

    echo "<h3>Recorrido de arrays multidimensionales</h3>";
    /*
    Si es un array bidimensional escalar podemos usar dos bucles
    for anidados y utilizamos los dos contadores de bucle para
    acceder a elementos individuales

    for( $i = 0; $i < count($notas); $i++ ) {
        echo "Recorrido de la fila $i<br>";
        for( $j = 0; $j < count($notas[$i]); $j++) {
            echo "Fila $i Columna $j: {$notas[$i][$j]}<br>";
        }
    }
    */

    foreach( $notas as $alumno => $trimestres ) {
        echo "Notas de $alumno:<br>";
        foreach( $trimestres as $trimestre => $modulos ) {
            echo "Notas del trimestre $trimestre:<br>";
            foreach( $modulos as $modulo => $nota) {
                echo "Módulo: $modulo Nota: $nota<br>";
            }
            echo "-----------------------------<br>";
        }
        echo "=================================<br>";
    }

    echo "El array con los coches<br>";
    foreach( $coches as $matricula => $coche ) {
        echo "Coche con matrícula: $matricula<br>";
        foreach( $coche as $clave => $valor ) {
            echo "$clave: $valor<br>";
        }
        echo "-----------------------<br>";
    }
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
<br>
<br>


    </body>
</html>