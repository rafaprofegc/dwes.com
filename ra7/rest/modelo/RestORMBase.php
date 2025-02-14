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
            // Compruebo si hay datos para un filtro
            $datos_filtrado = $this->getFiltro();
            $clausula_where = "";
            if( $datos_filtrado ) {
                $columnas = array_keys($datos_filtrado);
                // WHERE descripcion LIKE :descripcion AND referencia LIKE :referencia AND ...
                foreach($columnas as $columna ) {
                    $clausula_where.= "$columna LIKE :$columna AND ";
                }
                $clausula_where = "WHERE " . rtrim($clausula_where, "AND ");
            }

            $sql = "SELECT * FROM {$this->tabla} ";
            $sql.= $clausula_where;

            $stmt = $this->pdo->prepare($sql);
            if( $clausula_where ) {
                foreach($datos_filtrado as $columna => $valor) {
                    $stmt->bindValue(":$columna", "%$valor%");
                }
            }

            $datos = [];
            $clase = $this->getClaseEntidad();
            if( $stmt->execute() ) {
                while( $fila = $stmt->fetch() ) {
                    $datos[] = new $clase($fila);
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
            $resultado['codigo'] = "422 Unprocessable Entity";
        }
        return $resultado;
        
    }
    
    public function update(mixed $id): array {
        try {
            $objeto = $this->validarDatos(false);

            $datos = $objeto->toArray();

            $sql = "UPDATE {$this->tabla} ";
            $clausula_set = "SET ";
            foreach( $datos as $propiedad => $valor) {
                $clausula_set.= "$propiedad = :$propiedad, ";
            }
            $clausula_set = rtrim($clausula_set, ", ");

            $clausula_where = " WHERE {$this->clave_primaria} = :pk";

            $sql.= $clausula_set . $clausula_where;

            $stmt = $this->pdo->prepare($sql);
            foreach($datos as $propiedad => $valor) {
                $stmt->bindValue(":$propiedad", $valor);
            }
            $stmt->bindValue(":pk", $id);
            if( $stmt->execute() ) {
                if( $stmt->rowCount() == 1 ) {
                    $resultado['exito'] = True;
                    $resultado['datos'] = null;
                    $resultado['codigo'] = "204 No Content";
                    $resultado['error'] = null;
                }
                else {
                    $resultado['exito'] = False;
                    $resultado['datos'] = null;
                    $resultado['codigo'] = "422 Unprocessable Entity";
                    $resultado['error'] = [4001, 'No existe una fila con clave $id'];
                }
            }
        }
        catch(Exception $e) {
            $resultado['exito'] = False;
            $resultado['datos'] = null;
            $resultado['codigo'] = "422 Unprocessable Entity";
            $resultado['error'] = [$e->getCode(), $e->getMessage()];
        }
        return $resultado;
    }

    public function delete(mixed $id): array {
        try {
            $sql = "DELETE FROM {$this->tabla} WHERE {$this->clave_primaria} = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            if( $stmt->execute() ) {
                $resultado['error'] = null;
                $resultado['codigo'] = "204 No Content";
                $resultado['datos'] = null;
                $resultado['exito'] = true;
            }
            else {
                $resultado['exito'] = false;
                $resultado['codigo'] = "404 Not Found";
                $resultado['datos'] = null;
                $resultado['error'] = null;
            }
        }
        catch(Exception $e) {
            $resultado['exito'] = false;
            $resultado['codigo'] = "422 Unprocessable Entity";
            $resultado['datos'] = null;
            $resultado['error'] = [$e->getCode(), $e->getMessage()];
        }
        return $resultado;
    }
    
    public abstract function getClaseEntidad();
    public abstract function validarDatos(bool $nuevo = true);
    public abstract function getFiltro(): array;
}