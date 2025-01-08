<?php
namespace orm\bd;

/* Clase Database según el patrón Singleton:

    - Solo se permite una instancia de esta clase
    - Un constructor privado que crea un objeto PDO
    - 2 métodos: 
        getInstance()   -> Devuelve la instancia de la clase. Si no existe, la crea
        getConexion()   -> Devuelve la conexión de la BD.
*/

use orm\error\ORMExcepcion;
use \PDO;
use PDOException;

class Database {

    private static $instance = null;
    private PDO $pdo;

    private function __construct() {
        //$dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
        //$dsn = "mysql:host=cpd.iesgrancapitan.org;port=9992;dbname=rlozano;charset=utf8mb4";
        $dsn = "oci:dbname=192.168.12.70:1521/XEPDB1;charset=utf8";
        
        //$dsn = "oci:dbname=//cpd.iesgrancapitan.org:9990/XEPDB1;charset=utf8";
        $usuario = "rlozano";
        $clave = "usuario";
        $opciones = [
            PDO::ATTR_ERRMODE                   => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE        => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES          => false,
            PDO::ATTR_CASE                      => PDO::CASE_LOWER
        ];
        
        try {
            $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
        }
        catch(PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_CONEXION_BD, 
                                        ORMExcepcion::ERROR_FATAL);
        }
    }

    public static function getInstance(): Database {
        if( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConexion() {
        return $this->pdo;
    }
}

?>