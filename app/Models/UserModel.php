<?php
class Usuario {
    private $conn;
    private $table = 'usuario';

    public $id;
    public $email;
    public $password;
    public $id_rol;
    // ... resto de propiedades

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para el login
    public function emailExists() {
        // Query para buscar por email
        $query = "SELECT id, nombre, apellido, password, id_rol, usuario_name 
                  FROM " . $this->table . " 
                  WHERE email = ? OR usuario_name = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        // ... bindeo de parámetros y ejecución ...
        
        // Si encuentras el usuario, llenas las propiedades $this->id, $this->id_rol, etc.
    }
}
?>