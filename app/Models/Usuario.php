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
        
        try{
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
        }catch(Exception $e){
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    public function editar(){
        $query = "UPDATE " . $this->table . "
                    SET 
                    dni = :dni, 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    telefono = :telefono, 
                    email = :email, 
                    usuario_name = :usuario_name, 
                    calle = :calle, 
                    id_localidad = :id_localidad";

        // Lógica Condicional para la Contraseña: Solo agregar la columna si se proporciona una nueva.
        if (!empty($this->password)) {
            $query .= ", password = :password";
        }

        // CLÁUSULA WHERE CRÍTICA: Asegura que solo se actualice el registro con el ID del objeto.
        $query .= " WHERE id = :id"; 

        $stmt = $this->conn->prepare($query);

        //Limpieza de datos (igual que en tu código)
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido)); 
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->usuario_name = htmlspecialchars(strip_tags($this->usuario_name));
        $this->calle = htmlspecialchars(strip_tags($this->calle));

        // Vincular parámetros comunes
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':usuario_name', $this->usuario_name);
        $stmt->bindParam(':calle', $this->calle);
        $stmt->bindParam(':id_localidad', $this->id_localidad);

        // Vincular parámetro ID (Cláusula WHERE)
        $stmt->bindParam(':id', $this->id); // El ID debe estar en la propiedad $this->id

        // Vincular Contraseña (Condicional)
        if (!empty($this->password)) {
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // Ejecutar y retornar resultado
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $email 
     * @param string $nuevoNombreUsuario 
     * @param string $nuevaContrasenia 
     * @return bool 
     */
    public function actualizarCredencialesPorEmail(string $email, string $nuevoNombreUsuario, string $nuevaContrasenia): bool {
        
        //Cifrar la nueva contraseña
        $hashContrasenia = password_hash($nuevaContrasenia, PASSWORD_DEFAULT);
        try {
            $db = (new Database())->connect();
        } catch (\PDOException $e) {
            error_log("Error de conexión al actualizar credenciales: " . $e->getMessage());
            return false;
        }

        $sql = "UPDATE usuario 
                SET usuario_name = :nombreUsuario, password = :hashContrasenia 
                WHERE email = :email";

        try {
            $stmt = $db->prepare($sql);
            
            //Ejecutar la declaración con los parámetros
            $resultado = $stmt->execute([
                ':nombreUsuario' => $nuevoNombreUsuario,
                ':hashContrasenia' => $hashContrasenia,
                ':email' => $email
            ]);

            //Verificar si se actualizó al menos una fila
            return $resultado && $stmt->rowCount() > 0;

        } catch (\PDOException $e) {
            // Manejo de errores de la base de datos (ej. loguear el error)
            error_log("Error al actualizar credenciales en DB: " . $e->getMessage());
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
    // En app/models/Usuario.php

    public function listarProfesores() {
        $query = "SELECT 
                    u.id, 
                    u.dni, 
                    u.nombre, 
                    u.apellido, 
                    u.calle,
                    u.telefono,
                    u.email, 
                    u.fecha_inicio,
                    l.descripcion as localidad_nombre, 
                    r.descripcion as rol_nombre,
                    -- Agregamos la columna 'carreras_nombre' usando GROUP_CONCAT
                    GROUP_CONCAT(c.descripcion SEPARATOR ', ') as carreras_nombre 
                  FROM " . $this->table . " u
                  INNER JOIN localidad l ON u.id_localidad = l.id
                  INNER JOIN rol r ON u.id_rol = r.id
                  -- LEFT JOIN para traer la información de las carreras
                  LEFT JOIN usuario_carrera uc ON u.id = uc.id_usuario
                  LEFT JOIN carrera c ON uc.id_carrera = c.id
                  WHERE u.id_rol = 'P' AND u.activo = 1
                  -- Agrupamos por usuario para que GROUP_CONCAT funcione correctamente
                  GROUP BY u.id 
                  ORDER BY u.apellido ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
        /**
         * Lista todos los usuarios activos con rol 'A' (Alumno).
         */
        // En app/models/Usuario.php

    public function listarAlumnos() {
        $query = "SELECT 
                    u.id, 
                    u.dni, 
                    u.nombre, 
                    u.apellido, 
                    u.email, 
                    u.calle,
                    u.telefono,
                    l.descripcion as localidad_nombre, 
                    r.descripcion as rol_nombre,
                    -- Agregamos la columna 'carreras_nombre' usando GROUP_CONCAT
                    GROUP_CONCAT(c.descripcion SEPARATOR ', ') as carreras_nombre 
                  FROM " . $this->table . " u
                  INNER JOIN localidad l ON u.id_localidad = l.id
                  INNER JOIN rol r ON u.id_rol = r.id
                  -- LEFT JOIN para traer la información de las carreras
                  LEFT JOIN usuario_carrera uc ON u.id = uc.id_usuario
                  LEFT JOIN carrera c ON uc.id_carrera = c.id
                  WHERE u.id_rol = 'A' AND u.activo = 1
                  -- Agrupamos por usuario para que GROUP_CONCAT funcione correctamente
                  GROUP BY u.id 
                  ORDER BY u.apellido ASC";

        $stmt = $this->conn->prepare($query);
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
    }//

    /**
     * Obtiene las carreras asignadas a un usuario específico.
     */
    public function obtenerCarrerasPorUsuario($id_usuario) {
        $query = "SELECT c.id, c.descripcion
                  FROM usuario_carrera uc
                  INNER JOIN carrera c ON uc.id_carrera = c.id
                  WHERE uc.id_usuario = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /**
     * Elimina las asignaciones de carrera anteriores e inserta las nuevas.
     */
    public function actualizarCarreras($id_usuario, array $carreras_ids) {
        // Si no hay carreras seleccionadas, solo debe eliminar las antiguas
        if (empty($carreras_ids)) {
            $query_delete = "DELETE FROM usuario_carrera WHERE id_usuario = ?";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->bindParam(1, $id_usuario);
            return $stmt_delete->execute();
        }

        $this->conn->beginTransaction(); // Iniciar la transacción

        try {
            // ELIMINAR asignaciones antiguas
            $query_delete = "DELETE FROM usuario_carrera WHERE id_usuario = ?";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->bindParam(1, $id_usuario);
            if (!$stmt_delete->execute()) {
                $this->conn->rollBack();
                return false;
            }

            //INSERTAR nuevas asignaciones
            $query_insert = "INSERT INTO usuario_carrera (id_usuario, id_carrera) VALUES (?, ?)";
            $stmt_insert = $this->conn->prepare($query_insert);

            foreach ($carreras_ids as $carrera_id) {
                $carrera_id_int = (int) $carrera_id;
                $stmt_insert->bindParam(1, $id_usuario);
                $stmt_insert->bindParam(2, $carrera_id_int);

                if (!$stmt_insert->execute()) {
                    $this->conn->rollBack();
                    return false;
                }
            }

            $this->conn->commit(); // Confirmar la transacción
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar carreras: " . $e->getMessage());
            return false;
        }
    } 
    
    /**
     * Lista Alumnos activos (rol='A') que están en CUALQUIERA de las carreras del profesor.
     */
    public function listarAlumnosPorCarreraDeProfesor($id_profesor) {
        // Agregamos 'c.descripcion as carrera_nombre' al SELECT
        // Agregamos 'INNER JOIN carrera c' para obtener el nombre
        $query = "SELECT u.id, u.dni, u.nombre, u.apellido, u.telefono, u.email, u.calle,
                     r.descripcion as rol_nombre, 
                     l.descripcion as localidad_nombre, 
                     u.id_rol,
                     c.descripcion as carrera_nombre, 
                     uc_alumno.id_carrera as id_carrera
              FROM " . $this->table . " u
              INNER JOIN usuario_carrera uc_alumno ON u.id = uc_alumno.id_usuario
              INNER JOIN carrera c ON uc_alumno.id_carrera = c.id 
              INNER JOIN localidad l ON u.id_localidad = l.id
              INNER JOIN rol r ON u.id_rol = r.id
              WHERE u.activo = 1 
              AND u.id_rol = 'A'
              AND uc_alumno.id_carrera IN (
                  SELECT id_carrera FROM usuario_carrera 
                  WHERE id_usuario = :id_profesor
              )
              ORDER BY c.descripcion ASC, u.apellido ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_profesor', $id_profesor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /**
    * Elimina la relación específica de un usuario con una carrera.
    * Esto da de baja al alumno SÓLO de esa carrera.
    */
    public function eliminarRelacionUsuarioCarrera($id_usuario, $id_carrera) {
        $query = "DELETE FROM usuario_carrera 
                  WHERE id_usuario = :id_usuario AND id_carrera = :id_carrera";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);

        return $stmt->execute();
    }


}