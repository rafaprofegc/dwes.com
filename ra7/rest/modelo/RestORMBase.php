<?php
namespace rest\modelo;

use orm\bd\Database;
use Exception;
use PDO;
use rest\entidad\Articulo;

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
                    $datos[] = new Articulo($fila);
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
                $articulo = new Articulo($fila);
                $resultado['exito'] = True;
                $resultado['error'] = null;
                $resultado['datos'] = $articulo;
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

    public function insert(): array {
        // Insertar los datos
        try {
            $objeto = $this->validarDatos();

            $datos = $objeto->toArray();
            $sql = "INSERT INTO {$this->tabla} ";
            $clausula_values = "VALUES (";
            foreach( $datos as $propiedad => $valor) {
                $clausula_values.= ":$propiedad, ";
            }
            $clausula_values = rtrim($clausula_values, ", ") . ")";
            $sql.= $clausula_values;

            $stmt = $this->pdo->prepare($sql);
            foreach($datos as $propiedad => $valor) {
                $stmt->bindValue(":$propiedad", $valor);
            }
            if( $stmt->execute() ) {
                $resultado['exito'] = True;
                $resultado['error'] = null;
                // Si se ha creado el artículo con referencia acin0010
                // hay que enviar una cabecera Location: /articulos/acin0010
                $resultado['datos'] = "/{$this->tabla}s/{$datos[$this->clave_primaria]}";
                $resultado['codigo'] = "201 Created";
            }
            else {
                $resultado['exito'] = False;
                $resultado['error'] = null;
                $resultado['datos'] = null;
                $resultado['codigo'] = "422 Unprocessable Entity";
            }
        }
        catch( Exception $e) {
            $resultado['exito'] = False;
            $resultado['error'] = [$e->getCode(), $e->getMessage()];
            $resultado['datos'] = null;
            $resultado['codigo'] = "400 Bad Request";
        }
        return $resultado;
        
    }
    
    public function update(mixed $id, array $fila): array {
        return [];
    }
    
    public abstract function getClaseEntidad();
    public abstract function validarDatos();
}