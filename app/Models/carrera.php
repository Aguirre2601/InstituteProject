<?php
class Carrera {
    private $conn;
    private $table = 'carrera';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodas() {
        $query = "SELECT id, descripcion FROM " . $this->table . " ORDER BY descripcion ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Quizás necesites saber qué carreras cursa un usuario específico
    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT c.id, c.descripcion 
                  FROM " . $this->table . " c
                  INNER JOIN usuario_carrera uc ON c.id = uc.id_carrera
                  WHERE uc.id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}