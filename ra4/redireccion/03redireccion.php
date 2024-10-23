<?php
/*
    REDIRECCIONES
    -------------

    1ª Opción: Redirección permanente

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: <url>");

    2ª Opción: Redirección temporal
    header("Location: <url>", true, 302);

    En la redirección temporal el código de estado puede ser 307, indicando al cliente
    que en la 2ª petición (la redirigida) lo haga con el mismo método que la petición
    original.

*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    inicio_html("Redirecciones", ["/estilos/general.css", "/estilos/bh.css"]);
?>
<header>Opciones de la aplicación</header>
<form method="POST" action="">
    <div id="bh">
        <button class="bh" type="submit" name="operacion" value="1">Ver el catálogo</button>
        <button class="bh" type="submit" name="operacion" value="2">Venta Online</button>
        <button class="bh" type="submit" name="operacion" value="3">Quiénes somos</button>
        <button class="bh" type="submit" name="operacion" value="4">Contacto</button>
    </div>
</form>

<?php

    fin_html();
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST") {
    if( isset( $_POST['operacion'] ) ) {
        $operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_NUMBER_INT);
        $protocolo = $_SERVER['SERVER_PROTOCOL'];

        switch( intval($operacion)) {
            case 1: {
                header("$protocolo 301 Moved Permanently");
                header("Location: /ra4/redireccion/catalogo.php");
                break;
            }
            case 2: {
                header("$protocolo 301 Moved Permanently");
                header("Location: /ra4/redireccion/venta_online.php");
                break;
            }
            default: {
                header("$protocolo 404 Not found");
            }
        }
    }
}
?>