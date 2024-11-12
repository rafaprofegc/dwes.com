<?php
class Empleado {
    
    public string $nif;
    public string $nombre;
    public string $apellidos;
    public ?float $salario;
    public ?Direccion $direccion;

    public array $cc;

    const float IRPF = 0.2;
    const float SS = 0.05;
    const float SALARIO_BASE = 2000;

    public function __construct(string $nif, string $nombre, 
                                string $apellidos, ?float $salario = null,
                                Direccion $dir = null, array $cc = []) {
        $this->nif = $nif;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->salario = $salario ? $salario : Empleado::SALARIO_BASE;
        $this->direccion = $dir;
        $this->cc = $cc;

    }
    
    public function __destruct() {
        echo "<p>Se está destruyendo el objeto {$this->nif}</p>";
    }

    // 9º Promoción del constructor
    // Las propiedades no pueden declarse. Ya se declaran como parámetros
    // en el constructor
    /*
    public function __construct(public string $nif, public string $nombre, 
                                public string $apellidos, public float $salario = 2000) {}
    */


    public function getSalarioNeto(): ?float {
        if( $this->salario != null ) 
            $salario_neto = $this->salario - ( $this->salario * Empleado::IRPF +
                                               $this->salario * Empleado::SS);
        else
            $salario_neto = null;

        return $salario_neto;
    }

    // 10º Objetos como argumentos
    public function esIgual(Empleado $otro_empleado): bool {
        return $this == $otro_empleado;
    }

    // 11º Devolución de objetos 
    public function salarioDuplicado(): Empleado {
        $emp = new Empleado($this->nif, $this->nombre, $this->apellidos,
                            $this->salario * 2);

        return $emp;
    }

    public function __toString() :string {
        $cadena = "<br>" . self::class . "{$this->nif} {$this->nombre} {$this->apellidos}<br>";
        $cadena.= "{$this->direccion}<br>";
        $cadena.= implode("-", $this->cc) . "<br>";

        return $cadena;
    }

    public function __clone() :void {
        $this->direccion = clone $this->direccion;
    }

}
?>