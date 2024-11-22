<?php

function generar_token(array $usuario) {

    $jwt = "";

    // Codificamos los datos en formato JSON
    $cabecera = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode($usuario);

    // Covierto la cabecera y payload a base64
    $cabecera_base64 = base64_encode($cabecera);
    $payload_base64 = base64_encode($payload);

    // Reemplazo caracteres +, / y = en la codificación base64
    $cabecera_base64_limpio = str_replace(["+", "/", "="],["-", "_", ""], $cabecera_base64);
    $payload_base64_limpio = str_replace(["+", "/", "="],["-", "_", ""], $payload_base64);

    $clave = leer_clave();
    
    // Creo la firma
    $firma = hash_hmac("sha256", 
                       $cabecera_base64_limpio . "." . $payload_base64_limpio,
                       $clave, true);
    $firma_base64 = base64_encode($firma);
    $firma_base64_limpio = str_replace(["+", "/", "="],["-", "_", ""], $firma_base64);
    
    $jwt = $cabecera_base64_limpio . "." . $payload_base64_limpio . "." . $firma_base64_limpio;

    return $jwt;
}

function verificar_token($jwt): mixed {

    // Compruebo que el token tiene 3 partes
    $partes = explode(".", $jwt);

    if( count($partes) != 3 ) {
        // Token no válido
        return false;
    }

    // Separo las partes del jwt
    list($cabecera_base64_limpio, $payload_base64_limpio, $firma_base64_limpio) = $partes;

    // Obtengo la clave
    $clave = leer_clave();

    $firma = hash_hmac("sha256", 
                        $cabecera_base64_limpio . "." . $payload_base64_limpio,
                        $clave, true);

    $firma_base64 = base64_encode($firma);
    $firma_base64_nuevo = str_replace(['+','/','='],['-','_',''],$firma_base64);

    if( $firma_base64_limpio != $firma_base64_nuevo ) {
        return false;
    }

    // Aquí, el JWT es válido
    // Obtenemos el payload (datos de usuario) del JWT
    $payload_base64 = str_replace(['-','_',''], ['+','/','='], $payload_base64_limpio);
    $payload_json = base64_decode($payload_base64);
    $payload = json_decode($payload_json, true);

    return $payload;

}

function leer_clave(): string {
    $archivo_clave = $_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03clave.txt";
    if( file_exists($archivo_clave) ) {
        $fichero_clave = @fopen($archivo_clave, "r");
        $clave = fgets($fichero_clave);
        fclose($fichero_clave);
    }
    else {
        $clave = "abc123";
    }
    return $clave;
}

?>