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


/**
 * Asigna una lista de carreras al usuario recién creado.
 * Utiliza una transacción para asegurar la integridad de la asignación.
 * @param int $id_usuario ID del usuario recién creado.
 * @param array $carreras_ids IDs de las carreras seleccionadas.
 * @return bool True si es exitoso, false si falló.
 */
public function asignarCarreras($id_usuario, array $carreras_ids) {
    if (empty($carreras_ids)) {
        return true; // No hay nada que asignar
    }
    
    $this->conn->beginTransaction(); // Iniciar la transacción

    try {
        $query = "INSERT INTO usuario_carrera (id_usuario, id_carrera) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        foreach ($carreras_ids as $carrera_id) {
            $carrera_id_int = (int) $carrera_id; // Asegurar el tipo de dato
            $stmt->bindParam(1, $id_usuario);
            $stmt->bindParam(2, $carrera_id_int);
            
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }
        }
        
        $this->conn->commit(); // Confirmar la transacción
        return true;
        
    } catch (Exception $e) {
        $this->conn->rollBack(); // Deshacer si hay un error
        error_log("Error al asignar carreras: " . $e->getMessage()); // Para debug
        return false;
    }
}

    // DAR DE BAJA (Borrado Lógico)
    public function darBaja() {
        // 1. Definir la query: Solo actualizamos el estado y la fecha de fin
        $query = "UPDATE " . $this->table . "
                  SET activo = :activo, 
                      fecha_finalizacion = :fecha_fin
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

    //BUSCAR POR EMAIL O usuario_name (Para el Login)
    public function buscarPorEmail($email_o_user) {
        
        // Modificar la Query: Usamos el operador OR y parámetros posicionales (?)
        $query = "SELECT u.*, u.id_rol, u.password, u.activo
                  FROM " . $this->table . " u
                  WHERE u.email = ? OR u.usuario_name = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        // Vincular Parámetros: Bindeamos el valor ($email_o_user) dos veces.
        // El primer valor va a la primera posición (?) (email)
        $stmt->bindParam(1, $email_o_user);
        
        // El segundo valor va a la segunda posición (?) (usuario_name)
        $stmt->bindParam(2, $email_o_user);
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

    // Consulta base privada para ser reutilizada en listarProfesores y listarAlumnos
    private function getBaseQuery() {
        // La consulta trae todos los campos necesarios (incluyendo los JOINs)
        return "SELECT u.id, u.dni, u.nombre, u.apellido, u.telefono, u.email, u.calle, u.fecha_inicio, u.usuario_name, 
                       r.descripcion as rol_nombre, l.descripcion as localidad_nombre, u.id_rol
                FROM " . $this->table . " u
                LEFT JOIN rol r ON u.id_rol = r.id
                LEFT JOIN localidad l ON u.id_localidad = l.id
                WHERE u.activo = 1 
                AND u.id_rol = ? -- Filtro por rol (posicional)
                ORDER BY u.apellido ASC";
    }

    /**
     * Lista todos los usuarios activos con rol 'P' (Profesor).
     */
    public function listarProfesores() {
        $rol = 'P';
        $query = $this->getBaseQuery();

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $rol);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Lista todos los usuarios activos con rol 'A' (Alumno).
     */
    public function listarAlumnos() {
        $rol = 'A';
        $query = $this->getBaseQuery();

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $rol);
        $stmt->execute();

        return $stmt;
    }
    /**
     * Lee un único registro de usuario por ID.
     */
    public function leerUno($id) {
        $query = "SELECT u.*, r.descripcion as rol_nombre 
                  FROM " . $this->table . " u
                  LEFT JOIN rol r ON u.id_rol = r.id
                  WHERE u.id = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}