<?php
namespace orm\error;

use Exception;
use orm\util\Html;

class ORMExcepcion extends Exception {

    protected int $codigo;
    protected int $nivel;
    protected ?array $punto_recuperacion;

    public const ERROR_FATAL = 1;
    public const ERROR_RECUPERABLE = 2;

    public const ERROR_CONEXION_BD = 4001;
    public const ERROR_GET = 4002;
    public const ERROR_INSERT = 4003;
    public const ERROR_UPDATE = 4004;


    public function __construct(Exception $e, int $codigo, int $nivel, array $pr = null) {
        parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
        $this->codigo = $codigo;
        $this->nivel = $nivel;
        $this->punto_recuperacion = $pr;

        $this->gestiona_error();
    }

    public function gestiona_error() {
        echo "<h3>Error de la aplicación</h3>";
        echo "<p>Mensaje: " . $this->getMessage() . "<br>";
        echo "Código Excepcion: " . $this->getCode() . "<br>";
        $archivo = explode("/", $this->getFile());
        $script = end($archivo);

        echo "Archivo: $script<br>";
        echo "Línea: " . $this->getLine() . "<br>";
        echo "Código ORM: " . $this->codigo . "<br>";
        echo "Nivel: " . $this->nivel . "</p>";

        if( $this->nivel == self::ERROR_FATAL ) {
            Html::fin();
            exit();
        }

        if( $this->punto_recuperacion ) {
            echo "<p><a href='{$this->punto_recuperacion['url']}'>{$this->punto_recuperacion['enlace']}</a></p>";
            Html::fin();
            exit();
        }
    }

    public static function mostrar_error(Exception $e) {
        echo "<h3>Error de la aplicación</h3>";
        echo "<p>Mensaje: " . $e->getMessage() . "<br>";
        echo "Código Excepcion: " . $e->getCode() . "<br>";
        $archivo = explode("/", $e->getFile());
        $script = end($archivo);
        echo "Archivo: $script<br>";
        echo "Línea: " . $e->getLine() . "<br>";
    }
}