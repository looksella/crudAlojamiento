<?php

class UserAlojamiento {
    private $conn;
    private $table = 'usuarios_alojamientos';

    public $id;
    public $id_usuario;
    public $id_alojamiento;

//creamos el constructor para la inyección de las dependencias
    public function __construct($db) {
        $this->conn = $db;
    }

//ahora agregamos la selección de alojamiento
    public function add() {
        // Verificar si ya existe la selección
        if ($this->exists()) {
            return false;
        }

        $query = "INSERT INTO {$this->table} (id_usuario, id_alojamiento) 
                  VALUES (:id_usuario, :id_alojamiento)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->bindParam(':id_alojamiento', $this->id_alojamiento);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    //verificamos si ya existe la selección
    public function exists() {
        $query = "SELECT id FROM {$this->table} 
                  WHERE id_usuario = :id_usuario AND id_alojamiento = :id_alojamiento 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->bindParam(':id_alojamiento', $this->id_alojamiento);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    //ahora eliminamos la selección
    public function remove() {
        $query = "DELETE FROM {$this->table} 
                  WHERE id_usuario = :id_usuario AND id_alojamiento = :id_alojamiento";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->bindParam(':id_alojamiento', $this->id_alojamiento);
        
        return $stmt->execute();
    }

    //se obtienen los alojamientos seleccionados por el usuario
    public function getUserAlojamientos($userId) {
        $query = "SELECT a.*, ua.fecha_seleccion 
                  FROM alojamientos a 
                  INNER JOIN {$this->table} ua ON a.id = ua.id_alojamiento 
                  WHERE ua.id_usuario = :id_usuario 
                  ORDER BY ua.fecha_seleccion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    //obtencion de los ids de los alojamientos seleccionados por el usuario
    public function getUserAlojamientoIds($userId) {
        $query = "SELECT id_alojamiento FROM {$this->table} 
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $userId);
        $stmt->execute();
        
        $result = $stmt->fetchAll();
        return array_column($result, 'id_alojamiento');
    }

    //contador de las selecciones del usuario
    public function countUserSelections($userId) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }

    //obtencion del total de las selecciones
    public function getTotalSelections() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }

    //eliminamos todas las selecciones del usuario
    public function removeAllUserSelections($userId) {
        $query = "DELETE FROM {$this->table} WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $userId);
        
        return $stmt->execute();
    }
}