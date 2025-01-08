<?php
/*
    Fechas en PHP
    -------------

    Para gestionar fechas en PHP:

    - Clase DateTime     -> Almacena y gestiona una fecha y hora
    - Clase DateInterval -> Almacena y gestiona un intervalo de tiempo
    - Clase DatePeriod   -> Almacena y gestiona un periodo de tiempo.
    
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Fechas en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Fechas en PHP</header>";

echo "<h3>La clase DateTime</h3>";
$mi_fecha_nacimiento = new DateTime();

// Fecha y hora actual en UTC
$formato_fecha = "j/n/Y G:i:s";
echo "<p>La fecha y hora UTC : " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// Fecha y hora actual en España
$mi_fecha_nacimiento = new DateTime(null, new DateTimeZone("Europe/Madrid"));
echo "<p>La fecha y hora actual: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// Poner por defecto la zona horaria
date_default_timezone_set("Europe/Madrid");
ini_set("date.timezone", "Europe/Madrid");

// Fecha y hora actual en la zona horaria modificada.
$mi_fecha_nacimiento = new DateTime();
echo "<p>La fecha y hora actual: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// Indicar una fecha concreta en el objeto DateTime
// 24 de febrero del año actual
$mi_fecha_nacimiento = new DateTime("2/24");
echo "<p>La fecha y hora actual: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// 31 de octubre de 2012
$mi_fecha_nacimiento = new DateTime("31.10.2012");
echo "<p>La fecha y hora actual: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// 26 de noviembre de 1985
$mi_fecha_nacimiento = new DateTime("1985");
echo "<p>La fecha y hora en 1985 : " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

// 15 de diciembre de 2024 a las 18:15:20
$mi_fecha_nacimiento = new DateTime("15.12.2024 181520");
echo "<p>La fecha y hora: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
       <legend>Introduce dos datos de fecha</legend>
       
       <label for="fecha_date">Fecha 1</label>
       <input type="date" name="fecha_date" id="fecha_date">

       <label for="fecha_texto">Fecha 2</label>
       <input type="text" name="fecha_texto" id="fecha_texto" placeholder="dd/mm/yyyy"
         pattern="[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}">

    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar fechas">
</form>
<?php

if( $_SERVER['REQUEST_METHOD'] == "POST") {
    $fecha_date = filter_input(INPUT_POST, 'fecha_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_texto = filter_input(INPUT_POST, 'fecha_texto', FILTER_SANITIZE_SPECIAL_CHARS);

    echo "<p>Fechas desde el formulario son $fecha_date y $fecha_texto</p>";

    // Validación de las fechas
    try {
        $fecha1 = new DateTime($fecha_date);
        $fecha_texto = str_replace("/", ".", $fecha_texto);
        
        $fecha2 = new DateTime($fecha_texto);
        
        echo "<p>Fecha1 : " . $fecha1->format($formato_fecha) . "</p>";
        echo "<p>Fecha2 : " . $fecha2->format($formato_fecha) . "</p>";

    }
    catch(DateMalformedStringException $dmse) {
        echo "<p>Error al convertir las fechas:<br>";
        echo "Código: " . $dmse->getCode() . "<br>";
        echo "Mensaje: " . $dmse->getMessage() . "<br>";
    }
    
}

echo "<h3>Método createFromFormat()</h3>";
/*
    Método createFromFormat()
    -------------------------

    Campos de fecha y hora
    - d     -> Día con ceros iniciales (2 dígitos)
    - j     -> Día sin ceros iniciales

    - m     -> Mes con ceros iniciales (2 dígitos)
    - n     -> Mes sin ceros iniciales

    - y     -> Año con dos dígitos
    - Y     -> Año con cuatro dígitos

    - G     -> Hora de 0 a 23
    - i     -> Minutos de 00 a 59
    - s     -> Segundos de 00 a 59
*/

$formato_sin_ceros = "j/n/Y";
$mi_fecha_nacimiento = DateTime::createFromFormat($formato_sin_ceros, "20/5/2001");
echo "<p>Mi fecha de nacimiento es: " . $mi_fecha_nacimiento->format($formato_fecha);

$formato_con_ceros = "d/m/y";
$mi_fecha_nacimiento = DateTime::createFromFormat($formato_con_ceros, "5/3/98");
echo "<p>Mi fecha de nacimiento es: " . $mi_fecha_nacimiento->format($formato_fecha);

// Si quiero convertir una fecha procedente de un campo texto del formulario
// Lo mejor es usar el constructor, pero si usamos createFromFormat() hay que
// verificar que el valor de devolución no es false

$formato_esp = "d/m/Y";
$mi_fecha_nacimiento = DateTime::createFromFormat($formato_esp, $fecha_texto);
echo $mi_fecha_nacimiento ? $mi_fecha_nacimiento->format($formato_fecha) : "<br>Error de conversión";

/*
    Modificar un valor de fecha/hora
    --------------------------------

Métodos setDate() y setTime()

Permiten modificar la fecha completa y la hora  completa respectivamente.
*/

// Método setDate(). Modifico todos los campos
$mi_fecha_nacimiento = DateTime::createFromFormat($formato_esp, "23/8/2000");
$mi_fecha_nacimiento->setDate(1997, 9, 3);
echo "<p>Mi fecha de nacimiento: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

$mi_fecha_nacimiento->setTime(23,59,55);
echo "<p>Mi fecha de nacimiento: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

/*
    Clase DateInterval
    ------------------

    El constructor recibe una especificación de un intervalo con el siguiente formato:

        "P<nº>Y<nº>M<nº>D<nº>WT<nº>H<nº>M<nº>S"

        Ej:
        2 años y 3 dias:    "P2Y3D"
        5 meses y 4 horas:  "P5MT4H"

    Los tipos de unidades desde la escala mayor a la menor, de izquierda a derecha
    No puedo poner "P5M2Y"

    La especificación de formato se puede indicar también mediante una cadena de fecha/hora
    según ISO8601. ¡Cuidado! Sin desbordamiento

    Ej: "P0002-00-05T10:15:50";


    Para modificar una fecha/hora con los campos individuales que me interesen utilizo
    el método DateTime::add() para añadir a la fecha y DateTime::sub() para restar
    a la fecha, pasando como argumento un objeto DateInterval


*/

echo "<h3>Modificar fecha/hora con add(), sub() y DateInterval</h3>";
$intervalo = "P4M";
$mi_fecha_nacimiento = new DateTime("23-10-1996 14:15:50");
//$mi_fecha_nacimiento->add( new DateInterval($intervalo) );
echo "<p>Mi fecha de nacimiento: " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

$intervalo = "P1Y2DT13H15M10S";
$mi_fecha_nacimiento->add( new DateInterval($intervalo) );
echo "<p>Mi fecha de nacimiento: ($intervalo) " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

$intervalo = "P0005-02-31T03:05:10";
$mi_fecha_nacimiento->add( new DateInterval($intervalo) );
echo "<p>Mi fecha de nacimiento: ($intervalo) " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

$intervalo = "P2Y3MT5H";
$mi_fecha_nacimiento->sub( new DateInterval($intervalo) );
echo "<p>Mi fecha de nacimiento: ($intervalo) " . $mi_fecha_nacimiento->format($formato_fecha) . "</p>";

/*
        Clase DatePeriod
        ----------------

        Representa un periodo de tiempo entre dos fechas. Puede crearse de varias formas:
        1ª forma: Fecha de inicio, fecha de fin e intervalo de tiempo
        2ª forma: Fecha de inicio, intervalo de tiempo y nº de repeticiones.
        3ª forma: Con una cadena ISO

*/

$inicio = new DateTime("23-10-2005");
$intervalo = new DateInterval("P10D");
$fin = new DateTime("14-05-2006");

echo "<p>";
$periodo = new DatePeriod($inicio, $intervalo, $fin);
foreach( $periodo as $fecha) {
    echo "Fecha nueva: " . $fecha->format($formato_fecha) . "<br>";
}
echo "</p>";

$inicio = new DateTime("25.11.2024");
$intervalo = new DateInterval("P4D");
$repeticiones = 7;
echo "<p>";
$periodo = new DatePeriod($inicio, $intervalo, $repeticiones, DatePeriod::EXCLUDE_START_DATE);
foreach( $periodo as $fecha) {
    echo "Fecha nueva: " . $fecha->format($formato_fecha) . "<br>";
}
echo "</p>";

echo "<p>";
$iso = "R5/2025-02-27T15:30:45Z/P1W";
$periodo = new DatePeriod($iso);
foreach( $periodo as $fecha) {
    echo "Fecha nueva: " . $fecha->format($formato_fecha) . "<br>";
}
echo "</p>";





fin_html();
?>