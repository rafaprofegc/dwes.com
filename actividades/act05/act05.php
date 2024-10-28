<?php
/*
    Actividad05 según el enunciado en Actividades RA3 - Proceso de formularios.pdf
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

ini_set("upload_max_filesize", 500 * 1024);

// Array de destinos
$destinos = ['paris'     => Array('nombre' => 'Paris', 'precio' => 100),
             'londres'   => Array('nombre' => 'Londres', 'precio' => 120),
             'estocolmo' => Array('nombre' => 'Estocolmo', 'precio' => 200),
             'edinburgo' => Array('nombre' => 'Edinburgo', 'precio' => 175),
             'praga'     => Array('nombre' => 'Praga', 'precio' => 125),
             'viena'     => Array('nombre' => 'Viena', 'precio' => 150)];

// Array de compañías
$compañias = ['miair'     => Array('nombre' => 'MiAir', 'precio' => 0),
             'airfly'   => Array('nombre' => 'AirFly', 'precio' => 50),
             'vuelaconmigo' => Array('nombre' => 'VuelaConmigo', 'precio' => 75),
             'apedalesair' => Array('nombre' => 'ApedalesAir', 'precio' => 150)];

$hoteles = [3 => 0,
            4 => 40,
            5 => 100 ];
            
$extras = ['vg'     => 200,
           'bt'     => 30,
           '2m'     => 20,
           'sv'     => 30,
];

// Página autogenerada: El formulario se presenta con GET y el proceso se hace con POST

inicio_html("Actividad 05", ["/estilos/general.css", "/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] == "POST") {
    // Procesa el formulario
    // Si hay sticky form, se inicializan las variables 
    // con los datos del formulario para inicializar los valores 
    // de los controles del formulario.

    $responsable = filter_input(INPUT_POST, 'responsable', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
    $telefono = preg_match("/[0-9]{9}/", $telefono) == 0 ? "": $telefono;

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_SPECIAL_CHARS);
    $destino = array_key_exists($destino, $destinos) ? $destino : False;

    $compañia = filter_input(INPUT_POST, 'compañia', FILTER_SANITIZE_SPECIAL_CHARS);
    $compañia = array_key_exists($compañia, $compañias) ? $compañia : False;

    $hotel = filter_input(INPUT_POST, 'hotel', FILTER_SANITIZE_NUMBER_INT);   
    $hotel = filter_var($hotel, FILTER_VALIDATE_INT, 
                Array('min_range' => 3, 'max_range' => 5, 'default'   => 3 ));
    
    $hotel = $hotel >= 3 && $hotel <= 5 ? $hotel : 3;

    $desayuno = isset($_POST['desayuno']) && $_POST['desayuno'] == "On";

    $personas = filter_input(INPUT_POST, 'personas', FILTER_SANITIZE_NUMBER_INT);   
    $personas = filter_var($personas, FILTER_VALIDATE_INT, 
                Array('min_range' => 5, 'max_range' => 10, 'default'   => 5 ));

    $dias = filter_input(INPUT_POST, 'dias', FILTER_SANITIZE_NUMBER_INT);   
    $dias = $dias == 5 || $dias == 10 || $dias == 15 ? $dias : False;

    $extras_recibido = filter_input(INPUT_POST, 'extras[]', FILTER_SANITIZE_SPECIAL_CHARS, 
                                    FILTER_REQUIRE_ARRAY);
    
    $extras_ok = True;
    foreach( $extras_recibido as $clave => $valor) {
        if( !array_key_exists($clave, $extras) ) {
            $extras_ok = False;
            break;
        }
    }
    

    // Datos se han recibido, saneado y validado.
    // Se genera el presupuesto

    $total = 0;
    if( $destino ) {

    }
    
                                                         

    





}

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    // Poner el formulario si no es sticky form

    // Si es sticky form, inicializar los valores de los controles
    // del formulario con valores por defecto.
}

    // Si es sticky form, el formulario viene aquí

?>
<header>Presupuesto de viaje</header>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=500*1024?>">
    <fieldset>
        <legend>Datos del viaje</legend>
        <label for="responsable">Responsable del grupo</label>
        <input type="text" name="responsable" id="responsable" size="40" required>

        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" size="10" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" size="30" required>

        <label for="destino">Destino</label>
        <select name="destino" id="destino" size="1">
<?php
        foreach( $destinos as $clave => $valor ) {
            echo "<option value='$clave'>{$valor['nombre']}</option>";
        }
?>
        </select>

        <label for="compañia">Compañía aérea</label>
        <select name="compañia" id="compañia" size="1">
<?php
        foreach( $compañias as $clave => $valor ) {
            echo "<option value='$clave'>{$valor['nombre']}</option>";
        }
?>
        </select>
        
        <label for="hotel">Hotel</label>
        <select name="hotel" id="hotel" size="1">
<?php
        foreach( $hoteles as $clave => $valor ) {
            echo "<option value='$clave'>$clave * ($valor €/p/d)</option>";
        }
?>
        </select>

        <label for="desayuno">Desayuno incluido</label>
        <input type="checkbox" name="desayuno" id="desayuno">

        <label for="personas">Nº Personas</label>
        <input type="number" min="5" max="10" value="5" name="personas" id="personas">

        <label for="dias">Nº de días</label>
        <div>
            <input type="radio" name="dias" id="dias_5" value="5">5
            <input type="radio" name="dias" id="dias_10" value="10">10
            <input type="radio" name="dias" id="dias_15" value="15">15
        </div>

        <label for="extras[]">Extras</label>
        <div>
            <input type="checkbox" name="extras['vg']" id="extras_1">Visita guiada<br>
            <input type="checkbox" name="extras['bt']" id="extras_2">Bus turístico<br>
            <input type="checkbox" name="extras['2m']" id="extras_3">2ª Maleta facturada<br>
            <input type="checkbox" name="extras['sv']" id="extras_4">Seguro de viaje<br>
        </div>

        <label>Copia del libro de familia</label>
        <input type="file" name="libro" id="libro">
    </fieldset>
</form>
<?php
    fin_html();
?>