<?php
/* ENCABEZADOS
   -----------

   Ejemplo de uso de los encabezados. Enviamos contenido diferente de HTML
   para lo que tenemos que usar el encabezado Content-type

   Listar en formato de tabla los archivos del directorio ra4/archivos
   y que el usuario pueda descargar cualquiera de ellos

   No hay descarga directa. Lectura del contenido del archivo con una función
   de PHP y poner el contenido en la respuesta con su cabecera.


*/
define("DIRECTORIO_ARCHIVOS", $_SERVER['DOCUMENT_ROOT'] . "/ra4/archivos");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

if( $_SERVER['REQUEST_METHOD'] == "GET" && !isset($_GET['archivo']) ) {
    
    inicio_html("Encabezados", ["/estilos/general.css", "/estilos/tablas.css"]);
    echo "<header>Descarga de archivos</header>";

    $lista_archivos = scandir(DIRECTORIO_ARCHIVOS);
    if( count($lista_archivos) > 0 ) {
        echo <<<TABLA
            <table>
                <caption>Archivos disponibles para su descarga</caption>
                <thead>
                    <tr><th>Archivo</th>
                    <th>Tipo</th>
                    <th>Tamaño (bytes)</th>
                    <th>Descarga</th>
                    <th>Petición GET</th>
                </thead>
                <tbody>
        TABLA;
        foreach( $lista_archivos as $archivo) {
            if( is_file(DIRECTORIO_ARCHIVOS . "/$archivo") ) {
                $tipo_mime = mime_content_type(DIRECTORIO_ARCHIVOS . "/$archivo");
                $tamaño = filesize(DIRECTORIO_ARCHIVOS . "/$archivo");
                echo "<tr>";
                echo "<td>$archivo</td>";
                echo "<td>$tipo_mime</td>";
                echo "<td>$tamaño</td>";
                echo <<<FORM
                    <td><form action="{$_SERVER['PHP_SELF']}" method="POST" target="_blank">
                       <input type="hidden" name="archivo" value="$archivo">
                       <input type="submit" name="operacion" value="Descarga"> 
                    </form></td>
                FORM;
                echo "<td><a href='{$_SERVER['PHP_SELF']}?archivo=$archivo'>$archivo</a></td>";
                //    <a href='dwes.com/ra4/encabezados/01dif..cont.php?archivo=chord.png'>chord.png</a>
                echo "</tr>";
            }
        }
        echo "</tbody>";
        echo "</table>";
    }
    fin_html();

}
elseif ($_SERVER['REQUEST_METHOD'] == "POST" 
        || $_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['archivo']) ) {
    
            // Descarga del archivo con POST o con GET
    $parametro = $_POST['archivo'] ?? $_GET['archivo'] ?? "Sin archivo";

    $archivo_saneado = filter_var($parametro, FILTER_SANITIZE_SPECIAL_CHARS);
    $tipo_mime = mime_content_type(DIRECTORIO_ARCHIVOS . "/$archivo_saneado");
    
    header("Content-type: $tipo_mime");
    header("Content-disposition: attachment;filename=$archivo_saneado");
    if( file_exists(DIRECTORIO_ARCHIVOS . "/$archivo_saneado") ) {
        header("Content-length: " . filesize(DIRECTORIO_ARCHIVOS . "/$archivo_saneado"));
        readfile(DIRECTORIO_ARCHIVOS . "/$archivo_saneado");
    }     
}
?>