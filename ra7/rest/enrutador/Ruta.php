<?php
namespace rest\enrutador;

class Ruta {
    protected string $verbo;
    protected string $path;
    protected string $clase;
    protected string $funcion;

    public function __construct( string $verbo, string $path, string $clase, string $funcion) {
        $this->verbo = $verbo;
        $this->path = $path;
        $this->clase = $clase;
        $this->funcion = $funcion;
    }

    public function getClase(): string {

    }

    public function getFuncion(): string {

    }

    public function getPath(): string {

    }

    public function esIgual(string $verbo, string $path): bool {
        
    }
}
?>