<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra5/util/Autocarga.php");
use util\Autocarga;

Autocarga::registra_autocarga();

use rpc\controlador\JsonRpcControlador;
$json_controlador = new JsonRpcControlador();
$json_controlador->manejarPeticion();
?>