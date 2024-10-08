<?php
    /* Funciones en PHP

    Conjunto de sentencias con un nombre asociado
    que se ejecutan a discreción del desarrollador,
    cuando es necesario.
    
    Invocación o llamada de la función: Sentencia que solicita
    la ejecución de la función, momento en el cual el flujo
    del programa se desvía a la primera sentencia de la función
    y comienza su ejecución.

    Las funciones pueden necesitar datos. Estos datos se les
    pasa en forma de parámetros o argumentos de la función
    en el momento de la invocación.

    Pueden devolver uno o varios valores al punto de invocación
    que puede posteriormente utilizarse en cualquier expresión.

    Tipos:
        - Internas, integradas o predefinidas -> Las propias del lenguaje.
        - Métodos -> Funciones de clases de objetos.
        - De usuario -> Las que el desarrollador define.

    3.1 Definición de la función

    - Se puede definir en cualquier parte del script.
    - Tiene una cabecera y un cuerpo
    - Sintaxis:
        function nombre_función( [arg1, arg2, ...]) {
            sentencias
        }
        - Nombre: cualquier identificador válido sin $
        - Lista de parámetros o argumento separados por coma.
        - El cuerpo de la función es el conjunto de sentencias entre {}

    3.3 Paso de parámetros
        - Parámetro: Dato que la función necesita para su ejecución.
        - Permite que las funciones se ejecuten múltiples veces
          con diferentes datos. El objetivo de las funciones es evitar
          código repetitivo.
        - Tipos de parámetros:
            - Parámetro formal -> El que se define en la cabecera de la función.
              Este parámetro es equivalente a una variable. Conocido simplemente
              como PARÁMETRO.
            - Parámetro real -> El que se emplea en la invocación. Puede ser una
              expresión cualquier que arroje un valor. Conocido como ARGUMENTO.

        - Los argumentos y los parámetros tienen una correspondecia por su posición
          en la definición y en la invocación. El primer argumento corresponde al
          primer parámetro, el segundo argumento al segundo parámetro, ...

        3.3.1 Paso de parámetros por valor y por referencia.

        - Paso por valor -> Consiste en copiar el valor del argumento en el parámetro
          Hay dos entidades: el parámetro de la función y el argumento de la llamada,
          que son independientes.

        - Paso por referencia -> Consiste en pasar al parámetro la referencia (dirección
          de memoria) del argumento. Por tanto, solo hay una entidad y si el parámetro
          dentro de la función se modifica, el nuevo valor es visible en el script
          principal.

        3.3.2 Parámetros con valor por defecto
        En la cabecera la función los parámetros pueden tener un valor por defecto (solo
        literales)
        Pueden ser arrays y null
        Permite invocar la función sin esos parámetros, los cuales adquieren como valor
        el valor por defecto definido en la cabecera de la función.

        Si la función combina parámetros obligatorios con parámetros con valor por defecto,
        los obligatorios se definen antes que los de por defecto, salvo que en la invocación
        usemos argumentos con nombre.

        3.3.3 Tipos de datos
        En cada parámetro podemos definir su tipo de datos, de forma tal que si en la
        invocación no pasamos un argumento del mismo tipo de datos primero intenta una 
        conversión implícita al tipo definido en el parámetro. Si no
        puede dispara TypeError. Si puede, la hace y la función continua.

        3.3.5 Parámetros con nombre
        En la invocación de la función paso el argumento con el nombre del parámetro.
        Esto permite utilizar un orden diferente en los argumentos al de los parámetros
        definidos en la cabecera de la función.

        3.4 Valor de devolución
        ¿Cómo se devuelve más de un valor? Con un array. Luego se recoge con list() o
        con un array.

        3.5 Ámbito y visibilidad de una variable
        - Ámbito -> Fragmento del programa donde una variable existe.
        - Visibilidad -> Fragmento del programa donde una variable existe y es accesible.

        Resumen:
        - En una función no se accede a variables definidas fuera de la función,
          salvo las declaradas globales (con global) o el array $GLOBLAS.
        - Si se modifica el valor de una variable globa, su valor persiste.
        - Las variables static conservan su valor entre invocaciones de la función,
          pero son locales en la función.
        - Los parámetros de una función son como variables locales de la función.

    3.7 Recursividad
      Una función se invoca a si misma.


    */

    define("PI",3.14159);

    function area_triangulo($base, $altura) {
        $area = $base * $altura / 2;

        return $area;
    }

    function area_triangulo2(&$base, &$altura) {
        echo "1.- Dentro de la función: $base y $altura<br>";
        $base = 10;
        $altura = 20;

        $area = $base * $altura / 2;

        echo "2.- Dentro de la función: $base y $altura<br>";

        return $area;
    }

    function volumen_cilindro($radio, $altura = 10) {
        $area_base = PI * $radio ** 2;
        $volumen = $area_base * $altura;

        return $volumen;
    }

    function area_rectangulo(float $base, float $altura) {
        echo "Función a_r: " . gettype($base) . "<br>";
        echo "Función a_r: " . gettype($altura) . "<br>";

        return $base * $altura;
    }

    function media_aritmetica( ...$numeros ) {
        $suma = 0;
        foreach( $numeros as $numero) {
            $suma += $numero;
        }

        return $suma / count($numeros);

        // func_num_args(); -> Devuelve el nº de argumentos pasados

    }

    function circulo_y_circunferencia($radio): array {

        $resultado[] = PI * $radio ** 2;
        $resultado[] = 2 * PI * $radio;

        return $resultado;
    }

    function area_rectangulo2($base, $altura): ?float {
        if( $base < 0 || $altura < 0 ) {
            $area = null;
        }
        else {
            $area = $base * $altura;
        }

        return $area;

    }

    function Suma() {
        //global $a, $b;

        //$resultado = $a + $b;
        $a = 9;
        $resultado = $a + $GLOBALS['b'];

        $resultado = $GLOBALS['a'] + $GLOBALS['b'];

        return $resultado;
    }

    function contador_ejecuciones() {
        static $numero_ejecuciones = 0;
        $numero_ejecuciones++;

        echo "La función se ha ejecutado $numero_ejecuciones veces<br>";
    }

    function factorial($numero) {
        /*
        !n = n * (n-1) * (n-2) * ... 1
        !n = n * !(n-1)
        */

        if( $numero == 1 ) {
            $factorial = 1;
        }
        else {
            $factorial = $numero * factorial($numero - 1);
        }

        return $factorial;

    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Funciones</title>
    </head>
    <body>
        <h1>Funciones</h1>
<?php
    $base = 8;
    $altura = 3;

    // 3.2 Invocación o ejecución de la función
    $area = area_triangulo($base, $altura);

    echo "El triángulo con base $base y altura $altura tiene de área $area<br>";

    // Paso por valor
    $area = area_triangulo2($base, $altura);
    echo "El triángulo con base $base y altura $altura tiene de área $area<br>";

    // En el paso por valor permite que el argumento sea una expresión
    // cualquiera
    //$area = area_triangulo2(9, $base / 2);
    echo "El triángulo con base 9 y altura 4 tiene de área $area<br>";

    // Paso por referencia
    // En la llamada a la función usamos & antes del argumento.
    // En este caso, el argumento es obligatorio que sea una variable.
    $base = 8;
    $altura = 3;
    $area = area_triangulo2($base, $altura);
    echo "El triángulo con base $base y altura $altura tiene de área $area<br>";

    // Paso de parámetros con nombre
    $area = area_triangulo($altura = 5, $base = 10);
    echo "El triángulo con base $base y altura $altura tiene de área $area<br>";

    // Parámetros por defecto
    $volumen = volumen_cilindro(8, 9);
    echo "El volumen del cilindro con radio 8 y altura 9 es: $volumen<br>";
    $volumen = volumen_cilindro(10);
    echo "El volumen del cilindro con radio 10 y altura (por defecto) es: $volumen<br>";

    // Tipos de datos en los parámetros
    $area = area_rectangulo("8", 9);
    echo "El área del rectángulo es $area<br>";

    // Número de argumento variable
    $media = media_aritmetica(3, $area, 8, 9, 2.5, $volumen );
    echo "La media de los números anteriores es $media<br>";

    // Devolución de más de un valor
    $circulo_y_circunferencia = circulo_y_circunferencia(5);
    echo "El área del círculo con radio 5 es {$circulo_y_circunferencia[0]} y la 
    longitud de la circunferencia es {$circulo_y_circunferencia[1]}<br>";

    list($v1, $v2) = circulo_y_circunferencia(6);
    echo "El área es $v1 y la circunferencia es $v2<br>";

    // Valor null en la devolución
    $area = area_rectangulo2(-3, 9);
    echo ($area ? "El área es $area<br>" : "Algún parámetro es negativo<br>");

    $area = area_rectangulo2(3, 9);
    echo ($area ? "El área es $area<br>" : "Algún parámetro es negativo<br>");

    // Ámbito y visibilidad de las variables
    $a = 3;
    $b = 8;
    $resultado = Suma();
    echo "El valor de a es $a, el de b es $b, y la suma es $resultado-<br>";


    // Variables estáticas
    contador_ejecuciones();
    contador_ejecuciones();
    contador_ejecuciones();
    contador_ejecuciones();
    contador_ejecuciones();

    // Función recursiva
    $numeros = [ 6, 4, 9, 2];
    foreach( $numeros as $numero ){
        $factorial = factorial($numero);
        echo "El factorial de $numero es $factorial<br>";
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


    </body>
</html>
