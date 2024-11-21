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

function mostrar_excepcion($e) {
    echo "<h3>Error en la aplicación</h3>";
    echo "<p>Tipo de excepción: " . $e::class . "</p>";
    echo "<p>Mensaje de error: " . $e->getMessage() . "</p>";
    echo "<p>Código de error: " . $e->getCode() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
}

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

// Ejemplo 3: Se contemplan 2 excepciones
try {
    $x = strpos("h", "hola", 16);

    $numero = "a";
    $cuadrado = $numero ** 2;
    echo "<p>El cuadro de número es $numero</p>";
}
catch( ValueError $ve ) {
    mostrar_excepcion($ve);
}
catch( TypeError $te ) {
    mostrar_excepcion($te);
}

try {
    $x = strpos("h", "hola", 16);

    $numero = "a";
    $cuadrado = $numero ** 2;
    echo "<p>El cuadro de número es $numero</p>";
}
catch( TypeError | ValueError $e ) {
    mostrar_excepcion($e);
}
fin_html();

// Ejemplo 5: Cláusula finally. Se ejecuta el código dentro de
// finally ocurra, o no, la excepción.
echo "<h3>Cláusula finally</h3>";
try {
    $puntero = fopen("manuel@gmail.com.log","r");
    $numero_lineas = "#";
    while( $linea = fgets($puntero) ) {
        $numero_lineas += 1;
    }

    // Si cierro aquí el archivo y se dispara una
    // excepción, el archivo se queda abierto
    echo "<p>El número de líneas es $numero_lineas</p>";
}
catch ( TypeError $te ) {
    mostrar_excepcion($te);
}
finally {
    // Operaciones de limpieza
    echo "<p>Cerrando el archivo</p>";
    fclose($puntero);
}

// Ejemplo 6: El desarrollador lanza excepciones
echo "<h3>Lanzamiento de excepciones</h3>";
try {
    if( !file_exists("manuel@gmail.com.logg") ) {
        throw new Exception("El archivo no existe", 1000);
    }
    else {
        $puntero = @fopen("manuel@gmail.com.logg","r");
        while( $fila = fgets($puntero) ) {
            echo $fila;
        }
    }
}
catch( Exception $e) {
    mostrar_excepcion($e);
}

// Ejemplo 7: Excepciones personalizadas
echo "<h3>Excepciones personalizadas</h3>";

class AperturaFicheroExcepcion extends Exception {
    protected array $punto_recuperacion;

    public function __construct(string $message, int $code, 
            string $url_pr, string $enlace_pr, Exception $previous = null) {
        
        parent::__construct($message, $code, $previous);
        $this->punto_recuperacion['url'] = $url_pr;
        $this->punto_recuperacion['enlace'] = $enlace_pr;
    }

    public function __toString(): string {
        return __CLASS__ . "[{$this->code}]: $this->message";
    }

    public function getPuntoRecuperacion(): array {
        return $this->punto_recuperacion;
    }
}

try {
    if( !file_exists("manuel@gmail.com.logg") ) {
        throw new AperturaFicheroExcepcion("El fichero no existe", 1000, 
        "http://dwes.com", "Ir al principio de la aplicación");
    }

    $puntero = fopen("manuel@gmail.com.logg", "r");

}
catch( AperturaFicheroExcepcion $afe ) {
    mostrar_excepcion($afe);
    $pr = $afe->getPuntoRecuperacion();
    echo "Punto de recuperación: <a href='{$pr['url']}'>{$pr['enlace']}</a></p>"; 
}
?>