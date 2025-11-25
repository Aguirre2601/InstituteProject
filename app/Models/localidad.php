<?php
class Localidad {
    private $conn;
    private $table = 'localidad';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas para llenar el <select name="id_localidad">
    public function obtenerTodas() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY descripcion ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}