<?php
// Autocarga
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra5/util/Autocarga.php");

use util\Autocarga;

Autocarga::registra_autocarga();

use mvc\controlador\Controlador;

// Iniciar sesión o recuperar la que ya estuviera
session_start();

$controlador = new Controlador();
$controlador->gestiona_peticion();

?>