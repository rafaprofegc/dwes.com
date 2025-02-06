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
        return $this->clase;
    }

    public function getFuncion(): string {
        return $this->funcion;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function esIgual(string $verbo, string $path): bool {
        return $this->verbo === $verbo && preg_match($this->path, $path); 
    }
}
?>