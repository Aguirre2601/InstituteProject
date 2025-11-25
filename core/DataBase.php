<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'instituto_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanza error si algo falla
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Devuelve objetos en vez de arrays
                PDO::ATTR_EMULATE_PREPARES => false, // Seguridad extra
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
            die(); // Detiene todo si no hay base de datos
        }

        return $this->conn;
    }
}