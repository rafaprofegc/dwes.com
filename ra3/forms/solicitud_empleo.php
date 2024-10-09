<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Proceso de formulario 1", ["/estilos/general.css"]);

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$clave = $_POST['clave'];
$perfil_likedin = $_POST['linkedin'];
$fecha_disponible = $_POST['fecha_disponible'];
$hora_entrevista = $_POST['hora_entrevista'];
$salario = $_POST['salario'];
$areas = $_POST['areas'];


echo "Nombre: $nombre<br>";
echo "Email: $email<br>";
echo "Clave: $clave<br>";

fin_html();
?>