<?php
define("PRECIO_BASE", 5);
define("INC_VEG", 2);
define("EXTRA_QUESO", 3);
define("BORDES_RELLENOS", 4);

function MuestraDatos($payload, $ingredientes, $vegetariana, $eq = false, $br = false) {

    echo "<h3>Situación actual de su pedido</h3>";
    echo "<p>Inicio de la compra: " . date('d/M/Y G:i:s', $_SESSION['inicio']) . "</p>"; 
    echo "<h4>Datos de entrega</h4>";
    echo "<p>Nombre: {$payload['nombre']}<br>";
    echo "Dirección: {$payload['direccion']}<br>";
    echo "Teléfono: {$payload['telefono']}</p>";

    echo "<h4>Tipo de pizza</h4>";
    echo "<p>" . ($vegetariana ? "Vegetariana" : "NO vegetariana") . "</p>";

    echo "<h4>Ingredientes:</h4><p>";
    $precio = PRECIO_BASE + ($vegetariana ? INC_VEG : 0);

    foreach( $ingredientes as $ingrediente ) {
        echo "{$ingrediente['nombre']} - {$ingrediente['precio']}<br>";
        $precio += $ingrediente['precio'];
    }
    echo "</p>";
    echo "<h4>Extras de la pizza</h4><p>";
    if( $eq ) {
        echo "Extra de queso<br>";
        $precio += EXTRA_QUESO;
    }
    if( $br ) {
        echo "Con los bordes rellenos<br>";
        $precio += BORDES_RELLENOS;
    }
    echo "</p>";
    echo "<p>Precio actual $precio  €</p>";
}