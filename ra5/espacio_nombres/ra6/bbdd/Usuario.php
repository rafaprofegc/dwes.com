<?php
namespace ra6\bbdd;

class Usuario {                 
    public string $login;
    protected string $clave;
    public string $perfil;

    public function __construct(string $l, string $c, string $p) {
        $this->login = $l;
        $this->clave = password_hash($c, PASSWORD_DEFAULT);
        $this->perfil = $p;
    }

    public function __toString(): string {
        return "$this->login $this->perfil";
    }
}
?>