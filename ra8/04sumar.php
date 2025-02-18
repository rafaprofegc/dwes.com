<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
use util\HTML;

HTML::inicio_html("Suma de dos números", ["/estilos/general.css"], ["/ra8/js/04await.js"]);
?>
<h3>Servicio JSON-RPC Matemáticas</h3>
<input type="text" name="n1" id="n1" size="5">
+
<input type="text" name="n2" id="n2" size="5">
=
<input type="text" name="resultado" id="resultado" size="5" readonly>
<button type="button" name="sumar" id="sumar">Sumar</button>
<?php
HTML::fin_html();
?>