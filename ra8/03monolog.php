<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

use util\HTML;

HTML::inicio_html("Biblioteca Monolog", ["/estilos/general.css"], []);

// 1º Creamos un logger
$logger = new Logger("app");

// 2º Generamos el archivo log
$archivo_log = new StreamHandler(__DIR__ . "/app.log", Level::Debug);

// 3º Configuramos el formato de línea para el archivo log
$formato = "[%datetime%] - %channel% %level_name%: %message% %context%\n";
$formatter = new LineFormatter($formato, "d/m/Y H:i:s", true, true);

// 4º Asignamos el formato al archivo
$archivo_log->setFormatter($formatter);

// 5º Asignamos el archivo log al logger
$logger->pushHandler($archivo_log);

// 6º Añadimos líneas al logger
$logger->debug("Esto es un mensaje de depuración");
$logger->info("El usuario se ha autenticado", ["usuario" => "pepe"]);
$logger->warning("Advertencia: el espacio en disco ha llegado al 90%");
$logger->error("Error en la conexión a la base de datos", ["error" => 500, "mensaje" => "El usuario no es correcto"]);
$logger->critical("¡Fallo crítico del sistema!");

$logger->close();

$log = fopen(__DIR__ . "/app.log", "r");
while( $linea = fgets($log) ) {
    echo $linea . "<br>";
}
fclose($log);

HTML::fin_html();
?>