<?php
/*
    EXCEPCIONES EN PHP
    ------------------

    - Excepción -> Condición anormal en la ejecución del código de la aplicación
      Puede provocar una interrupción de la aplación. Es un error del programa.

    - La gestión de excepciones es habitual en todos lo lenguajes de programación
    - Se permite capturar una excepción e implementar operaciones de recuperación,
      si es posible, y si no lo es, se puede hacer una salida ordenada e informar
      del error.

    - La implementación de la gestión de excepciones es bajo el paradigma POO.

    - Principios básicos en PHP:
        - Una excepción es un objeto que contiene información del error.
        - Flujo de una excepción:

            - Cuando ocurre una excepción se crea un objeto excepción y se lanza
              en el método que la causó.

            - Ese método puede elegir entre gestionar la excepción o pasársela
              al método o función que lo invocó.

            - En algún punto, hay que capturar la excepción y tratarla.

        - Las excepciones las puede lanzar el motor de PHP y estas generalmente
          se relacionan con violaciones del lenguaje, o manualmente por el
          desarrollador 

    - Gestión de excepciones en PHP

        try {
            // Código con una probabilidad de que
            // ocurra una excepción

            // Aquí se lanza una excepción

            // Aquí no se llega nunca


        }
        catch( <clase_excepcion> $var_exc) {
            // La variable $var_exc es un objeto de tipo <clase_excepcion>
            // con toda la información de la excepción.

            // Código que gestiona la excepción.

            // Al finalizar la ejecución de este bloque de código
            // el flujo continua después del bloque catch()
        
        }
        finally {
            // Código que se ejecuta tanto si ha ocurrido la excepción
            // como si no.

            // Tareas de limpieza que no pudieron hacerse al disparse la excepción
        }

        // Flujo del programa si no hay excepción o después de gestionarla si la hay
*/

require_once($_SERVER['DOCUMENT_ROOT']. "/includes/funciones.php");
inicio_html("Excepciones en PHP", ["/estilos/general.css"]);

// Ejemplo 1: Se lanza una excepción PHP sin gestionar
/*
$numero = "a";
echo "<p>Vamos a calcular el cuadro de número</p>";
$cuadrado = $numero ** 2;
echo "<p>El cuadro de número es $cuadrado</p>";
*/

// Ejemplo 2: Atrapamos la excepción
try  {
    $numero = "a";
    $cuadrado = $numero ** 2;
    echo "<p>El cuadro de número es $numero</p>";
}
catch(TypeError $te) {
    // Ver información del error
    echo "<h3>Error en la aplicación</h3>";
    echo "<p>Tipo de excepción: " . $te::class . "</p>";
    echo "<p>Mensaje de error: " . $te->getMessage() . "</p>";
    echo "<p>Código de error: " . $te->getCode() . "</p>";
    echo "<p>Archivo: " . $te->getFile() . "</p>";
    echo "<p>Línea: " . $te->getLine() . "</p>";

    if( gettype($numero) != "int") {
        $numero = 4;
    }
    $cuadrado = $numero ** 2;
    echo "<p>El cuadro de número es $cuadrado</p>";

}

fin_html();


?>