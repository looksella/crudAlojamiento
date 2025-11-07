<?php
/**
 * Clase User
 * Responsabilidad: Gestionar operaciones de usuarios en la base de datos
 * Principio SOLID: Single Responsibility Principle (SRP)
 * Principio SOLID: Dependency Inversion Principle (DIP) - Depende de abstracción (PDO)
 */
class User {
    private $conn;
    private $table = 'usuarios';

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;
    public $fecha_creacion;

    /**
     * Constructor - Inyección de dependencias
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo usuario
     */
    public function create() {
        $query = "INSERT INTO {$this->table} (nombre, email, password, rol) 
                  VALUES (:nombre, :email, :password, :rol)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = Validator::clean($this->nombre);
        $this->email = Validator::clean($this->email);
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->rol = $this->rol ?? 'usuario';
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':rol', $this->rol);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        $query = "SELECT id, nombre, email, rol, fecha_creacion 
                  FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Verificar si el email ya existe
     */
    public function emailExists($email) {
        $query = "SELECT id FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Verificar credenciales de login
     */
    public function verifyCredentials($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Actualizar usuario
     */
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET nombre = :nombre, email = :email 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = Validator::clean($this->nombre);
        $this->email = Validator::clean($this->email);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    /**
     * Obtener todos los usuarios
     */
    public function getAll() {
        $query = "SELECT id, nombre, email, rol, fecha_creacion 
                  FROM {$this->table} ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Contar usuarios
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }
}