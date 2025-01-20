<?php
namespace mvc\modelo\orm;

use orm\entidad\DireccionEnvio;
use orm\modelo\ORMDireccion_Envio;

class Mvc_Orm_Direccion_Envio extends ORMDireccion_Envio {
    public function getDireccionesCliente($nif) {
        $sql = "SELECT nif, id_dir_env, direccion, cp, poblacion, provincia, pais ";
        $sql.= "FROM {$this->tabla} ";
        $sql.= "WHERE nif = :nif";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nif", $nif);

        if( $stmt->execute() ) {
            while( $fila = $stmt->fetch() ) {
                $de = new DireccionEnvio($fila);
                $direcciones_envio[] = $de;
            }
            return $direcciones_envio;
        }
        return [];
    }
}
?>
