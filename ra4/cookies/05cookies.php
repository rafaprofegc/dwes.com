<?php
/*
    COOKIES
    -------
    - Dato que el servidor almacena en el HD del cliente.
    - Cualquier información alfanumérica de hasta 4 KB.
    - Usos:
        - Seguimiento de la sesión.
        - Mantener datos entre peticiones.
        - Detalles de inicio de sesión: usuario (ya no, salvo que esté cifrado)

    - Solo se puede leer desde el dominio del servidor que las creo, así otros
      servidores no tienen acceso.

    - Cookies de 3os. -> Las cookies que establece un sitio (servidor) web diferente
      al que se hizo la petición.

      Cualquier servidor puede acceder a las cookies de terceros.

    - Funcionamiento:

        Cliente                                     Servidor
        -------------------------------------       -----------------------------------
        GET /index.hmtl HTTP/1.1 -----------------> HTTP/1.1 200 Ok
                                                    Encabezados (Cache-control, Content-type,...)
                                                    Set-Cookie: nombre=valor
                                                    Set-Cookie: nombre=valor

                                                    <html>....
         <------------------------------------------
        Get /news.html HTTP/1.1
        Host: dwes.com
        Cookie= name=valor (la misma que recibió)---> 

        Ejemplo de uso de cookies: Preferencias de usuario
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

$colores_validos = ["white"       => "Blanco",
                    "black"       => "Negro",
                    "red"         => "Rojo",
                    "blue"        => "Azul",
                    "yellow"      => "Amarillo",
                    "brown"       => "Marrón",
                    "pink"        => "Rosa",
                    "green"       => "Verde" ];

if( $_SERVER['REQUEST_METHOD'] == "GET" ) {
    if( isset( $_COOKIE['color_fondo']) && isset($_COOKIE['color_texto'])) {
        $color_fondo = filter_input(INPUT_COOKIE, 'color_fondo', FILTER_SANITIZE_SPECIAL_CHARS);
        $color_texto = filter_input(INPUT_COOKIE, 'color_texto', FILTER_SANITIZE_SPECIAL_CHARS);

        if( ! array_key_exists($color_fondo, $colores_validos) || 
            ! array_key_exists($color_texto, $colores_validos) ) {
            $color_fondo = "white";
            $color_texto = "black";
        }
    }
    else {
        $color_fondo = "white";
        $color_texto = "black";
    }

    formulario_cookies($color_fondo, $color_texto);

}

elseif( $_SERVER['REQUEST_METHOD'] == "POST" ) {
    if( isset($_POST['color_fondo']) && isset($_POST['color_texto'])) {
        $color_fondo = filter_input(INPUT_POST, 'color_fondo', FILTER_SANITIZE_SPECIAL_CHARS);
        $color_texto = filter_input(INPUT_POST, 'color_texto', FILTER_SANITIZE_SPECIAL_CHARS);

        if( array_key_exists($color_fondo, $colores_validos) && 
            array_key_exists($color_texto, $colores_validos)) {
            setcookie("color_fondo", $color_fondo, time() + 60 * 60, "/ra4/cookies"); 
            setcookie("color_texto", $color_texto, time() + 60 * 60, "/ra4/cookies");
        }
    }
    formulario_cookies($color_fondo, $color_texto);
}

function formulario_cookies($color_fondo, $color_texto) {
    global $colores_validos;

    inicio_html("Cookies", ["/estilos/general.css", "/estilos/formulario.css"]);
    echo "<div style='background-color:$color_fondo;color:$color_texto'>";
    echo "<header>Gestión de cookies</header>";
    echo <<<FORM1
    <form method="POST" action="{$_SERVER['PHP_SELF']}">
        <fieldset>
            <legend>Selecciona los colores que quieras</legend>
            <label for="color_fondo">Color de fondo</label>
            <select name="color_fondo" size="1">
    FORM1;
    foreach( $colores_validos as $clave => $valor ) {
        echo "<option value='$clave'" . ($clave == $color_fondo ? "selected" : "") . ">$valor</option>";
    }
    echo <<<FORM2
            </select>
            <label for="color_texto">Color de texto</label>
            <select name="color_texto" size="1">
    FORM2;
    foreach( $colores_validos as $clave => $valor ) {
        echo "<option value='$clave'" . ($clave == $color_texto ? "selected" : "") . ">$valor</option>";
    }
    echo <<<FORM3
            </select>
        </fieldset>
        <input type="submit" name="operacion" value="Cambiar colores">
    </div>
    FORM3;
    fin_html();

}
?>