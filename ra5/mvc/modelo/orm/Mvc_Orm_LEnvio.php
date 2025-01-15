<?php
namespace mvc\modelo\orm;

use Exception;
use PDO;

class Mvc_Orm_LEnvio {
    protected PDO $pdo;

    public function __construct() {
        $dsn = "mysql:host=192.168.12.71;dbname=rlozano;charset=utf8mb4";
        $usuario = "rlozano";
        $clave = "usuario";
        $opciones = [ PDO::ATTR_ERRMODE     => PDO::ERRMODE_EXCEPTION,
                      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                      PDO::ATTR_EMULATE_PREPARES    => false ];

        $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
    }
    public function get_envios(string $nif) : array {
        if( isset($_SESSION['cliente'])) {
            $sql = "select nenvio, fecha, npedido, nlinea, referencia, ";
            $sql.= "descripcion, lenvio.unidades, precio, dto ";
            $sql.= "from lenvio inner join envio using(nenvio) ";
            $sql.= "inner join lpedido using(npedido,nlinea) ";
            $sql.= "inner join articulo using(referencia) ";
            $sql.= "where nif = :nif ";
            $sql.= "order by fecha desc";

            $stmt = $this->pdo->prepare($sql);          
            $stmt->bindValue(":nif", $nif);
            if( $stmt->execute() ) {
                return $stmt->fetchAll();
            }
            else {
                throw new Exception("Error al obtener los envios del cliente", 4004);
            }
        }
    }
}
?>