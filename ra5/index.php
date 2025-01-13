<?php
// Autocarga
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra5/util/Autocarga.php");

use util\Autocarga;

Autocarga::registra_autocarga();

use mvc\controlador\Controlador;

$controlador = new Controlador();
$controlador->gestiona_peticion();

?>