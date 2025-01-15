<?php
namespace mvc\modelo\orm;

use orm\entidad\Cliente;
use orm\modelo\ORMCliente;

class Mvc_Orm_Clientes extends ORMCliente {
    public function busca_cliente(string $email): object {
        $sql = "SELECT nif, nombre, apellidos, clave, iban, telefono, email, ventas ";
        $sql.= "FROM {$this->tabla} ";
        $sql.= "WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email);
        if( $stmt->execute() && $stmt->rowCount() == 1 ) {
            $fila = $stmt->fetch();
            $cliente = new Cliente($fila);
            return $cliente;
        }
        else {
            return null;
        }
    }
}
?>