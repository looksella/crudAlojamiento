<?php
/**
 * Clase Database
 * Responsabilidad: Gestionar la conexión a la base de datos
 * Principio SOLID: Single Responsibility Principle (SRP)
 */
class Database {
    private $host = 'localhost';
    private $db_name = 'alojamientos_db';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    /**
     * Obtener la conexión a la base de datos
     * @return PDO|null
     */
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch(PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return $this->conn;
    }

    /**
     * Cerrar la conexión
     */
    public function closeConnection() {
        $this->conn = null;
    }
}