<?php
class Usuario {
    public string $login;
    public string $nombre;
    public string $perfil;

    public string $archivo_log;

    private $arc_log;

    public const string PERFIL_ADM = 'Adm';
    public const string PERFIL_ESTANDAR = 'Est';
    public const string PERFIL_INVITADO = 'Inv';

    public function __construct(string $lo, string $no, string $pe, string $al) {
        $this->login = $lo;
        $this->nombre = $no;
        $this->perfil = $pe;
        $this->archivo_log = $al;

        $this->arc_log = fopen($this->archivo_log, 'a');

    }

    public function __toString(): string {
        return "{$this->login} - {$this->nombre} {$this->perfil}<br>";
    }

    public function registraActividad(string $descripcion): void {
        if( $this->arc_log ) {
            $formato_fecha = "d/m/Y G:i:s";
            $actividad = date($formato_fecha) . " -> " . $descripcion. "\n";
            fwrite($this->arc_log, $actividad);
        }
    }

    // Método mágico __sleep()
    public function __sleep(): array {
        
        // Tarea de limpieza. Cerramos el archivo log
        if( $this->arc_log ) fclose($this->arc_log);

        // Devolvemos las propiedades a serializar
        return Array('login', 'nombre', 'perfil', 'archivo_log');
    }

    // Método mágico __wakeup()
    public function __wakeup(): void {
        if( !$this->arc_log ) 
            $this->arc_log = fopen($this->archivo_log, 'a');
    }
  
}
?>