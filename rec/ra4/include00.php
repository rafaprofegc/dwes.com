<?php
function presentar_datos($payload) {
    $hora = date("d/m/Y H:i:s", $_SESSION['hora']);
    echo <<<DATOS
        <p>Usuario: {$payload['dni']}<br>
        Nombre: {$payload['nombre']}<br>
        Hora: {$hora}</p>
    DATOS;
}

$cursos = [ 
    'ofi'   => ['descripcion' => 'Ofimática', 'precio' => 10],
    'prog'  => ['descripción' => 'Programación', 'precio' =>	50],
    'ssoo'  => ['descripción' => 'Sistemas Operativos', 'precio' =>	20],
    'rep'   => ['descripción' => 'Reparación de PCs', 'precio' =>	30]
];
?>
