<?php
class ProfesorController{
    
    // Helper: Centralizar la verificación de permisos
    private function isProfesor() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'P') {
            // Si no es Profesor, redirigimos al login o a donde corresponda
            AuthController::redirectToDashboard(null); 
            return false;
        }
        return true;
    }

    // URL: /profesor/dashboard
    public function dashboard() {
        if (!$this->isProfesor()) return;

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        $id_profesor = $_SESSION['user_id'];

        // 1. OBTENER ALUMNOS FILTRADOS 
        $alumnos = $usuarioModel->listarAlumnosPorCarreraDeProfesor($id_profesor); 

        // 2. OBTENER INFORMACIÓN DEL PROFESOR
        $profesor = $usuarioModel->leerUno($id_profesor); 
        // 3. OBTENER CARRERAS DEL PROFESOR (Para el filtro combobox)
        // Esto nos permite llenar el <select> solo con las materias que él dicta
        $carreras_filtro = $usuarioModel->obtenerCarrerasPorUsuario($id_profesor);

        require_once ROOT_PATH . 'app/views/profesor/dashboard.php';
    }
    
    // URL: /profesor/darDeBajaAlumno/5 (El ID viene en $id)
    public function darDeBajaAlumno($id) {
        if (!$this->isProfesor() || !is_numeric($id)) return;

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        // 1. Buscamos el usuario para verificar que sea alumno
        $usuario = $usuarioModel->leerUno($id); // Este método lo agregaremos al Modelo
        
        if ($usuario && $usuario->id_rol === 'A') {
            // 2. Asignamos el ID al objeto del modelo para la baja
            $usuarioModel->id = $id;
            
            if ($usuarioModel->darBaja()) {
                $_SESSION['mensaje'] = "Alumno dado de baja lógicamente con éxito.";
            } else {
                $_SESSION['mensaje'] = "Error al dar de baja al alumno.";
            }
        } else {
            $_SESSION['mensaje'] = "Error: El usuario no existe o no es un Alumno.";
        }

        // Redireccionar al listado
        header("Location: " . '/profesor/dashboard');
        exit();
    }

    // URL: /profesor/vistaEditarPerfil (Muestra el formulario - MÉTODO GET)
    public function vistaEditarPerfil() {
        if (!$this->isProfesor()) return;

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        $localidadModel = new Localidad($db); 
        $carreraModel = new Carrera($db); // Necesario para el listado total

        $id_profesor = $_SESSION['user_id'];

        // 1. Obtener los datos actuales del perfil del profesor
        $profesor = $usuarioModel->leerUno($id_profesor); 

        // 2. Obtener la lista de TODAS las localidades
        $localidades = $localidadModel->obtenerTodas()->fetchAll(PDO::FETCH_OBJ);

        // 3. Obtener la lista de TODAS las carreras
        $carreras_totales = $carreraModel->obtenerTodas(); 

        // 4. Obtener las carreras ASIGNADAS al profesor
        $carreras_asignadas = $usuarioModel->obtenerCarrerasPorUsuario($id_profesor);

        // Convertir a un array simple de IDs para que la vista pueda chequear fácilmente
        $carreras_asignadas_ids = array_map(function($c){ return $c->id; }, $carreras_asignadas);

        if (!$profesor) {
            $_SESSION['mensaje'] = "Error: No se encontró el perfil para editar.";
            header("Location: /profesor/dashboard");
            exit();
        }

        require_once ROOT_PATH . 'app/views/profesor/perfil_editar.php';
    }

    // URL: /profesor/actualizarPerfil (Procesa el formulario - MÉTODO POST)
    public function actualizarPerfil() {
        if (!$this->isProfesor() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /profesor/dashboard");
            exit();
        }

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        $id_profesor = $_SESSION['user_id'];
        $usuarioModel->id = $id_profesor; 

        // Capturar las Carreras Seleccionadas
        $carreras_seleccionadas = filter_input(INPUT_POST, 'carreras', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($carreras_seleccionadas === null) {
            $carreras_seleccionadas = [];
        }

        // 1. Asignar datos personales al Modelo
        // ... (Tu código de asignación de propiedades se mantiene igual) ...
        $usuarioModel->dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_NUMBER_INT);
        $usuarioModel->nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $usuarioModel->password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT); 
        $usuarioModel->usuario_name = filter_input(INPUT_POST, 'usuario_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->calle = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->id_localidad = filter_input(INPUT_POST, 'id_localidad', FILTER_SANITIZE_NUMBER_INT);

        $mensaje = "Perfil actualizado con éxito.";
        $exito_perfil = $usuarioModel->editar(); // Ejecuta el UPDATE

        if ($exito_perfil) {
            // 2. Actualizar la asignación de Carreras
            if ($usuarioModel->actualizarCarrerasProfesor($id_profesor, $carreras_seleccionadas)) {
                 $mensaje = "Perfil y asignación de carreras actualizados con éxito.";
            } else {
                $mensaje = "Perfil actualizado, pero **falló** la asignación de carreras.";
            }
        } else {
            $mensaje = "Error al actualizar el perfil (DNI/Email duplicado).";
        }

        $_SESSION['mensaje'] = $mensaje;
        header("Location: " . '/profesor/dashboard');
        exit();
    }

}