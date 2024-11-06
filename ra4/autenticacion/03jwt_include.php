<?php

function generar_token(array $usuario, string $clave) {
    
    // Codificamos los datos en formato JSON
    $cabecera = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode($usuario);


}

?>