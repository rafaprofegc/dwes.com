<?php
if( $_SERVER['REQUEST_METHOD'] == "GET" ) {
    if( isset($_GET['imagen']) ) {
        $imagen = filter_input(INPUT_GET, 'imagen', FILTER_SANITIZE_SPECIAL_CHARS);

        $imagenes_disponibles = scandir($_SERVER['DOCUMENT_ROOT'] . "/ra4/imágenes");
        if( in_array($imagen, $imagenes_disponibles)) {
            $tipo_mime = mime_content_type($_SERVER['DOCUMENT_ROOT'] . "/ra4/imágenes/$imagen");
            header("Content-type: $tipo_mime");
            readfile($_SERVER['DOCUMENT_ROOT'] . "/ra4/imágenes/$imagen");
        }
    }
}
/*
$imagen = filter_input(INPUT_GET, 'imagen', FILTER_SANITIZE_NUMBER_INT);

$imagen_validada = filter_var($imagen, FILTER_VALIDATE_INT);


switch( intval($imagen) ) {
    case 1: {
        $archivo = "/var/www/dwes.com/ra4/imágenes/architecture.png";
        if( file_exists($archivo) ) {
            $tipo_mime = mime_content_type($archivo);
            header("Content-type: $tipo_mime");
            readfile($archivo);
        }
        break;
    }
    case 2: {
        $archivo = "/var/www/dwes.com/ra4/imágenes/chord.png";
        if( file_exists($archivo) ) {
            $tipo_mime = mime_content_type($archivo);
            header("Content-type: $tipo_mime");
            readfile($archivo);
        }
        break;
    }

}
*/

?>