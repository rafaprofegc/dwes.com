<?php
/*
    GESTIÓN DE LA CACHÉ DEL NAVEGADOR CON CABECERAS
    -----------------------------------------------

    El servidor puede comunicar al navegador si puede o no cachear el recurso
    solicitado y durante cuanto tiempo.

    Cabeceras que intervienen:
        - Expires: <fecha - hora>   -> Indica la fecha y hora en la que se considera el recurso
                                       reciente.
                                       Tiene que estar en formato RFC2371

                                       Wed, 23 Oct 2024 15:42:35 GMT

        - Cache-control: <valores>  -> Conjunto de valores que controlan la caché
                                       de una respuesta. Tiene precedencia sobre
                                       Expires. 

            no-cache    -> Cachea la página, pero antes de mostrarla al usuario
                           tiene que pedir validación al servidor.
            no-store    -> No se pude guardar la página en caché
            max-age: <segundos> -> Tiempo durante el cual el recurso se considera reciente.
            must-revalidate -> El navegador valida la página en el servidor si se ha superado
                               el max-age
            private | public -> Si es privada, solamente la puede cachear el navegador.
                                Si es pública, la puede cachear navegador y dispositivos
                                intermedios (proxy)

    Más documentación en: https://developer.mozilla.org/es/docs/Web/HTTP/Headers/Cache-Control
*/
$ahora = time();
$dentro_una_hora = $ahora + 60 * 60;

// Formato RFC2371 para Expire
// Wed, 23 Oct 2024 15:47:30 GMT
$formato_fecha = "%a, %d %b %Y %H:%M:%S GMT";
$caducidad = gmstrftime($formato_fecha, time() + 60 * 60);

//header("Expires: $caducidad");

//header("Cache-control: no-cache,max-age= 3600");

// Evitar que el navegador cachee una respuesta
$caducidad = gmdate("D, d M Y H:i:s", time()) . " GMT";
header("Expire: $caducidad");
header("Last Modified: $caducidad");
header("Cache-Control: no-store, no-cache, must-revalidate, private, max-age=0");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Caché del navegador", ["/estilos/general.css"]);
echo "<header>Probando la caché del navegador</header>";
echo "<h3>Este cambio se hace mientras la página está cacheada y reciente</h3>";
fin_html();
?>

