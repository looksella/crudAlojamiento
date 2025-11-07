<?php

class Alojamiento {
    private $conn;
    private $table = 'alojamientos';

    public $id;
    public $nombre;
    public $descripcion;
    public $ubicacion;
    public $precio;
    public $capacidad;
    public $habitaciones;
    public $banos;
    public $imagen;
    public $wifi;
    public $estacionamiento;
    public $piscina;
    public $disponible;

    //creamos el constructor para la inyección de dependencias
    public function __construct($db) {
        $this->conn = $db;
    }

   //crear un nuevo alojamiento
    public function create() {
        $query = "INSERT INTO {$this->table} 
                  (nombre, descripcion, ubicacion, precio, capacidad, habitaciones, banos, 
                   imagen, wifi, estacionamiento, piscina, disponible) 
                  VALUES (:nombre, :descripcion, :ubicacion, :precio, :capacidad, 
                          :habitaciones, :banos, :imagen, :wifi, :estacionamiento, 
                          :piscina, :disponible)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = Validator::clean($this->nombre);
        $this->descripcion = Validator::clean($this->descripcion);
        $this->ubicacion = Validator::clean($this->ubicacion);
        $this->imagen = Validator::clean($this->imagen);
        
        // Establecer valores por defecto
        $this->wifi = $this->wifi ?? false;
        $this->estacionamiento = $this->estacionamiento ?? false;
        $this->piscina = $this->piscina ?? false;
        $this->disponible = $this->disponible ?? true;
        
        // Bind de parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':capacidad', $this->capacidad);
        $stmt->bindParam(':habitaciones', $this->habitaciones);
        $stmt->bindParam(':banos', $this->banos);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':wifi', $this->wifi);
        $stmt->bindParam(':estacionamiento', $this->estacionamiento);
        $stmt->bindParam(':piscina', $this->piscina);
        $stmt->bindParam(':disponible', $this->disponible);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    //obtener todos los alojamientos disponibles
    public function getAll($disponible = true) {
        $query = "SELECT * FROM {$this->table}";
        
        if ($disponible !== null) {
            $query .= " WHERE disponible = :disponible";
        }
        
        $query .= " ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($disponible !== null) {
            $stmt->bindParam(':disponible', $disponible);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

   //busqueda por id
    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    //actualizar alojamiento
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      ubicacion = :ubicacion, 
                      precio = :precio, 
                      capacidad = :capacidad, 
                      habitaciones = :habitaciones, 
                      banos = :banos, 
                      imagen = :imagen, 
                      wifi = :wifi, 
                      estacionamiento = :estacionamiento, 
                      piscina = :piscina, 
                      disponible = :disponible 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = Validator::clean($this->nombre);
        $this->descripcion = Validator::clean($this->descripcion);
        $this->ubicacion = Validator::clean($this->ubicacion);
        $this->imagen = Validator::clean($this->imagen);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':capacidad', $this->capacidad);
        $stmt->bindParam(':habitaciones', $this->habitaciones);
        $stmt->bindParam(':banos', $this->banos);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':wifi', $this->wifi);
        $stmt->bindParam(':estacionamiento', $this->estacionamiento);
        $stmt->bindParam(':piscina', $this->piscina);
        $stmt->bindParam(':disponible', $this->disponible);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    //eliminar alojamiento
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    //buscar alojamientos
    public function search($search, $ubicacion = null, $precioMin = null, $precioMax = null) {
        $query = "SELECT * FROM {$this->table} WHERE disponible = 1";
        
        if (!empty($search)) {
            $query .= " AND (nombre LIKE :search OR descripcion LIKE :search)";
        }
        
        if (!empty($ubicacion)) {
            $query .= " AND ubicacion LIKE :ubicacion";
        }
        
        if ($precioMin !== null) {
            $query .= " AND precio >= :precioMin";
        }
        
        if ($precioMax !== null) {
            $query .= " AND precio <= :precioMax";
        }
        
        $query .= " ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        if (!empty($ubicacion)) {
            $ubicacionParam = "%{$ubicacion}%";
            $stmt->bindParam(':ubicacion', $ubicacionParam);
        }
        
        if ($precioMin !== null) {
            $stmt->bindParam(':precioMin', $precioMin);
        }
        
        if ($precioMax !== null) {
            $stmt->bindParam(':precioMax', $precioMax);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //contar alojamientos
    public function count($disponible = true) {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if ($disponible !== null) {
            $query .= " WHERE disponible = :disponible";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($disponible !== null) {
            $stmt->bindParam(':disponible', $disponible);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }
}