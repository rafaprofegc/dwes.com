<?php
namespace rest\modelo;

use orm\bd\Database;
use Exception;
use PDO;

abstract class RestORMBase {
    protected string $tabla;
    protected string $clave_primaria;

    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConexion();
    }

    public function getAll(): array {
        try {
            $sql = "SELECT * FROM {$this->tabla}";
            $stmt = $this->pdo->prepare($sql);
            $datos = [];
            if( $stmt->execute() ) {
                while( $fila = $stmt->fetch() ) {
                    $datos[] = $fila;
                }
            }
            $resultado['exito'] = True;
            $resultado['error'] = null;
            $resultado['datos'] = $datos;
            $resultado['codigo'] = "200 Ok";
        }
        catch(Exception $e) {
            $resultado['exito'] = False;
            $resultado['error'] = [$e->getCode(), $e->getMessage()];
            $resultado['datos'] = null;
            $resultado['codigo'] = "400 Bad Request";
        }
        return $resultado;

    }

    public function get(mixed $id): array {
        try {
            $sql = "SELECT * FROM {$this->tabla} WHERE {$this->clave_primaria} = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            if( $stmt->execute() && $stmt->rowCount() == 1 ) {
                $fila = $stmt->fetch();
                $resultado['exito'] = True;
                $resultado['error'] = null;
                $resultado['datos'] = $fila;
                $resultado['codigo'] = "200 Ok";
            }
            else {
                $resultado['exito'] = False;
                $resultado['error'] = null;
                $resultado['datos'] = null;
                $resultado['codigo'] = "404 Not Found";    
            }
        }
        catch(Exception $e ) {
            $resultado['exito'] = False;
            $resultado['error'] = [$e->getCode(), $e->getMessage()];
            $resultado['datos'] = null;
            $resultado['codigo'] = "400 Bad Request";
        }
        return $resultado;
    }

    public function insert(array $nueva_fila): array {
        return [];
    }
    
    public function update(mixed $id, array $fila): array {
        return [];
    }
    
    public abstract function getClaseEntidad();
}