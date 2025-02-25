<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use util\HTML;

HTML::inicio_html("Petición fetch con cadena de consulta", 
    ["/estilos/general.css", "/estilos/tablas.css"], 
    ["js/06await.js"]);

echo <<<FORM
<h2>Peticiones asíncronas</h2>
<h3>Búsqueda de artículos</h3>
<label for="descripcion">Descripción</label>
<input type="text" name="descripcion" id="descripcion" size="40">
<button type="button" name="buscar" id="buscar">Buscar artículos</button>
<div id="error"></div>
<hr>
<table>
    <thead>
        <tr>
            <th>Referencia</th>
            <th>Descripción</th>
            <th>PVP</th>
            <th>Descuento</th>
            <th>Und. Vendidas</th>
            <th>Und. Disponibles</th>
            <th>Fecha disponible</th>
            <th>Categoría</th>
            <th>Tipo IVA</th>
        </tr>
    <thead>
    <tbody id="cuerpo"></tbody>
</table>

<template id="fila_cuerpo">
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</template>
FORM;
HTML::fin_html();
?>