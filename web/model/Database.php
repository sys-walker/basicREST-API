<?php
class Database
{
    protected $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
        
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }           
    }

    public function select($query = "" , $params = [])
    {

        /*
        $query = "INSERT INTO users (name, email) VALUES (?, ?)";
        */
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);             
            $stmt->close();

            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }


    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );

            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }   
    }


    private function executeStatement2($query = "" , $params = [])
    {
       try {
            $stmt = $this->connection->prepare( $query );

            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if( $params ) {
                $stmt->bind_param($params[0], ...array_slice($params, 1));
            }

            $stmt->execute();

            // Check if the query was an INSERT and return the insert ID
            if (stripos(trim($query), 'INSERT') === 0) {
                $insertId = $this->connection->insert_id;
                $stmt->close();
                return $insertId;
            }

            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }  
    }


    public function insert($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement2( $query , $params );
            $result = $stmt; //response of creation
            // $stmt->close();

            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }


    /*
    public function insert($table = "", $table_fields = [], $params = []) {
        // Validación básica de parámetros
        if (empty($table) || empty($table_fields) || empty($params)) {
            throw new InvalidArgumentException("Parámetros incompletos");
        }
        
        // Verifica que el primer elemento de $params sea el string de tipos
        if (!is_string($params[0])) {
            throw new InvalidArgumentException("El primer elemento de params debe ser el string de tipos");
        }

        // Valida coincidencia entre campos y parámetros
        $expectedValuesCount = count($table_fields);
        $actualValuesCount = count($params) - 1; // Restamos el string de tipos
        if ($actualValuesCount !== $expectedValuesCount) {
            throw new InvalidArgumentException(
                "Número de valores incorrecto. Esperados: $expectedValuesCount, Recibidos: $actualValuesCount"
            );
        }

        try {
            // 1. Construye la consulta INSERT
            $fields = implode(", ", $table_fields);
            $placeholders = implode(", ", array_fill(0, $expectedValuesCount, "?"));
            $query = "INSERT INTO `$table` ($fields) VALUES ($placeholders)";

            // 2. Prepara la consulta
            $stmt = $this->mysqli->prepare($query);
            if (!$stmt) {
                throw new RuntimeException("Error al preparar INSERT: " . $this->mysqli->error);
            }

            // 3. Vincula parámetros dinámicamente
            $types = array_shift($params);
            $boundParams = array_merge([$types], $params);
            $this->_bindParameters($stmt, $boundParams);

            // 4. Ejecuta el INSERT
            if (!$stmt->execute()) {
                throw new RuntimeException("Error al ejecutar INSERT: " . $stmt->error);
            }

            // 5. Obtiene el ID insertado
            $inserted_id = $this->mysqli->insert_id;
            if ($inserted_id <= 0) {
                throw new RuntimeException("No se pudo obtener el ID insertado. ¿La tabla tiene AUTO_INCREMENT?");
            }

            // 6. Recupera el registro completo
            return $this->getInsertedRecord($table, $inserted_id);

        } catch (Exception $e) {
            // Loggear el error si es necesario
            throw $e; // Relanza para manejo externo
        } finally {
            // Limpieza garantizada de recursos
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }

    // Método auxiliar para binding seguro de parámetros
    private function _bindParameters(mysqli_stmt $stmt, array $params) {
        $bindNames = [];
        foreach ($params as $key => $value) {
            $bindNames[] = &$params[$key]; // Referencia requerida para bind_param
        }
        call_user_func_array([$stmt, 'bind_param'], $bindNames);
    }

    // Método auxiliar para obtener el registro insertado
    private function _getInsertedRecord($table, $id) {
        $query = "SELECT * FROM `$table` WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        
        if (!$stmt) {
            throw new RuntimeException("Error al preparar SELECT: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new RuntimeException("Error al ejecutar SELECT: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $record = $result->fetch_assoc();
        $stmt->close();

        if (!$record) {
            throw new RuntimeException("Registro insertado no encontrado");
        }

        return $record;
    }*/

}