<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra7/rest/util/Autocarga.php");

use util\Autocarga;
use rest\enrutador\Enrutador;

Autocarga::registraAutocarga();

$enrutador = new Enrutador();
$enrutador->despacha();

?>