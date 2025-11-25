<?php
class Usuario {
    private $conn;
    private $table = 'usuario';

    // Propiedades del objeto
    public $id;
    public $dni;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $password;
    public $usuario_name;
    public $calle;
    public $id_localidad;
    public $id_rol;
    public $fecha_inicio;
    public $fecha_finalizacion;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }
// Devuelve todos los registros de la tabla
    public function all() {
        // Creamos la query hacia la tabla del modelo
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        // Ejecutamos la query que devuelve multiples registros
        return $stmt->fetchAll();
    }

    // Devuelve un solo registro filtrado por id
    public function find($id) {
        // Creamos la query hacia la tabla del modelo filtrano por el id
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        // Ejecutamos la query y pasamos el parametro $id
        $stmt->execute([$id]);
        // Devolvemos el resultado de la consulta que tiene un solo registro
        return $stmt->fetch();
    }

    //LISTAR TODOS (Para el Dashboard del Director/Admin)
    public function listar() {
        $query = "SELECT u.id, u.dni, u.nombre, u.apellido, u.telefono, u.email, u.usuario_name,u.calle, u.fecha_inicio
                         r.descripcion as rol_nombre, l.descripcion as localidad_nombre
                  FROM " . $this->table . " u
                  LEFT JOIN rol r ON u.id_rol = r.id
                  LEFT JOIN localidad l ON u.id_localidad = l.id
                  WHERE u.activo = 1  
                  ORDER BY u.apellido ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREAR UN NUEVO USUARIO
    public function crear() {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        
        $fecha_actual = date("Y-m-d");
        $estado_activo = 1; 
        $query = "INSERT INTO " . $this->table . "
                  (dni, nombre, apellido, telefono, email, password, usuario_name, calle, id_rol, id_localidad, fecha_inicio, activo)
                  VALUES 
                  (:dni, :nombre, :apellido, :telefono, :email, :password, :usuario_name, :calle, :id_rol, :id_localidad, :fecha_inicio, :activo)";

        $stmt = $this->conn->prepare($query);

        // Limpieza de datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido)); 
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->usuario_name = htmlspecialchars(strip_tags($this->usuario_name));
        $this->calle = htmlspecialchars(strip_tags($this->calle));

        // Encriptar password
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular parámetros
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':usuario_name', $this->usuario_name);
        $stmt->bindParam(':calle', $this->calle);
        $stmt->bindParam(':id_rol', $this->id_rol);
        $stmt->bindParam(':id_localidad', $this->id_localidad);
        $stmt->bindParam(':fecha_inicio', $fecha_actual);
        $stmt->bindParam(':activo', $estado_activo);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    // DAR DE BAJA (Borrado Lógico)
    public function darBaja() {
        // 1. Definir la query: Solo actualizamos el estado y la fecha de fin
        $query = "UPDATE " . $this->table . "
                  SET activo = :activo, 
                      fecha_fin = :fecha_fin
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // 2. Preparar los datos
        // Asegúrate que la zona horaria esté definida en tu config
        $estado_inactivo = 0; // 0 = Falso/Inactivo
        $fecha_baja = date("Y-m-d");

        // 3. Vincular parámetros
        $stmt->bindParam(':activo', $estado_inactivo);
        $stmt->bindParam(':fecha_fin', $fecha_baja);
        $stmt->bindParam(':id', $this->id);

        // 4. Ejecutar
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    //BUSCAR POR EMAIL (Para el Login)
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    //INSCRIBIR EN CARRERA (Manejo de la tabla pivote usuario_carrera)
    public function inscribirCarrera($id_carrera) {
        $query = "INSERT INTO usuario_carrera (id_usuario, id_carrera) VALUES (:id_usuario, :id_carrera)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $this->id);
        $stmt->bindParam(':id_carrera', $id_carrera);
        return $stmt->execute();
    }
}