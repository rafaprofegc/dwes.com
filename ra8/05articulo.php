<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/vendor/autoload.php");

use util\HTML;

HTML::inicio_html("Búsqueda de artículos", ["/estilos/general.css"],
                  ["js/05xmlhttprequest.js"]);
?>
<h2>Búsqueda de artículos</h2>
<input type="text" name="referencia" id="referencia" size="10">
<button type="button" name="enviar" id="enviar">Buscar artículo</button>
<hr>
<div id="resultado"></div>
<?php
HTML::fin_html();
?>