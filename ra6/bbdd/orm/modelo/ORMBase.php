<?php
namespace orm\modelo;

use PDO;
use orm\bd\Database;
use orm\entidad\Entidad;
use orm\error\ORMExcepcion;
use PDOException;
use DateTime;

abstract class ORMBase {
    protected string $tabla;
    protected string $clave_primaria;

    protected PDO $pdo;

    public function __construct() {
        $instancia_database = Database::getInstance();
        $this->pdo = $instancia_database->getConexion();
    }

    /*
        Métodos para las operaciones CRUD
        ---------------------------------

        C   -> insert()             INSERT
        R   -> get() y getAll()     SELECT
        U   -> update()             UPDATE
        D   -> delete()             DELETE

    */
    public function get(mixed $id): ?Entidad  {

        try {
            $sql = "SELECT * FROM {$this->tabla} ";
            $sql.= "WHERE {$this->clave_primaria} = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id );
            if( $stmt->execute() ) {
                $fila = $stmt->fetch();
                $clase = $this->getClaseEntidad();
                $objeto = new $clase($fila);
                return $objeto;
            }
            return null;
        }
        catch(PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_GET, ORMExcepcion::ERROR_RECUPERABLE,
                            ['url'  => "/", 'enlace' => 'Raíz de la aplicación']);
        }
    }

    public function getAll(): array {
        try {
            $sql = "SELECT * FROM {$this->tabla}";
            $stmt = $this->pdo->query($sql);
            $filas = [];
            if( $stmt->execute() ) {
                while( $fila = $stmt->fetch()) {
                    $clase = $this->getClaseEntidad();
                    $objeto = new $clase($fila);
                    $filas[] = $objeto;
                }
            }
        }
        catch(PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_GET, ORMExcepcion::ERROR_RECUPERABLE,
                            ['url'  => "/", 'enlace' => 'Raíz de la aplicación']);
        }
        finally {
            return $filas;
        }
    }

    public function insert(Entidad $nueva_fila): bool {
        $array_objeto = $nueva_fila->toArray();
        $columnas = array_keys($array_objeto);

        $sql = "INSERT INTO {$this->tabla} ";
        $sql.= "VALUES( :" . implode(", :", $columnas) . ")";

        try {
            $stmt = $this->pdo->prepare($sql);
            foreach($array_objeto as $columna => $valor) {
                $nombre_tipo = Entidad::tipoPropiedad($nueva_fila, $columna);
                if( $nombre_tipo == \DateTime::class ) {
                    $stmt->bindValue(":$columna", $valor->format(Entidad::FECHA_HORA_MYSQL));
                }
                else {
                    $stmt->bindValue(":$columna", $valor);
                }

                
            }

            return $stmt->execute() && $stmt->rowCount() == 1;
        }
        catch( PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_INSERT,
                        ORMExcepcion::ERROR_RECUPERABLE, ['url' => '/', 'enlace' => 'Raíz de la aplicación']);
        }
    }

    public function update(mixed $id, Entidad $fila): bool {
        $array_objeto = $fila->toArray();

        $sql = "UPDATE {$this->tabla} SET ";
        foreach($array_objeto as $columna => $valor ) {
            $sql.= "$columna = :$columna, ";    
        }
        $sql = rtrim($sql, ", ");
        
        $sql.= " WHERE {$this->clave_primaria} = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            
            foreach( $array_objeto as $columna => $valor) {
                $nombre_tipo = Entidad::tipoPropiedad($fila, $columna);
                if( $nombre_tipo == DateTime::class ) {
                    $stmt->bindValue(":$columna", $valor->format(Entidad::FECHA_HORA_MYSQL));    
                }
                else {
                    $stmt->bindValue(":$columna", $valor);
                }
                
            }
            $stmt->bindValue(":id", $id);

            return $stmt->execute() && $stmt->rowCount() == 1;
        }
        catch( PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_UPDATE,
                            ORMExcepcion::ERROR_RECUPERABLE, ['url' => '/', 'enlace' => 'Raíz de la aplicación']);
        }
    }

    public function delete(mixed $id) : bool {
        $sql = "DELETE FROM {$this->tabla} WHERE {$this->clave_primaria } = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            return $stmt->execute() && $stmt->rowCount() == 1;
        }
        catch( PDOException $pdoe) {
            throw new ORMExcepcion($pdoe, ORMExcepcion::ERROR_UPDATE,
                            ORMExcepcion::ERROR_RECUPERABLE, ['url' => '/', 'enlace' => 'Raíz de la aplicación']);
        }
    }

    // Método abstracto a implementar por las clases derivadas
    public abstract function getClaseEntidad();
}

?>