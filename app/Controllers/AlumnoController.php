<?php
class AlumnoController{
    // URL: /alumno/vistaEditarPerfil (Muestra el formulario - MÉTODO GET)
    public function vistaEditarPerfil() {
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        $localidadModel = new Localidad($db); 
        $carreraModel = new Carrera($db); // Necesario para el listado total

        $id_alumno = $_SESSION['user_id'];

        // 1. Obtener los datos actuales del perfil del alumno
        $alumno = $usuarioModel->leerUno($id_alumno); 

        // 2. Obtener la lista de TODAS las localidades
        $localidades = $localidadModel->obtenerTodas();

        // 3. Obtener la lista de TODAS las carreras
        $carreras_totales = $carreraModel->obtenerTodas(); 

        // 4. Obtener las carreras ASIGNADAS al alumno
        $carreras_asignadas = $usuarioModel->obtenerCarrerasPorUsuario($id_alumno);

        // Convertir a un array simple de IDs para que la vista pueda chequear fácilmente
        $carreras_asignadas_ids = array_map(function($c){ return $c->id; }, $carreras_asignadas);

        require_once ROOT_PATH . 'app/views/alumno/perfil_editar.php';
    }

    // URL: /alumno/actualizarPerfil (Procesa el formulario - MÉTODO POST)
    public function actualizarPerfil() {
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        $id_alumno = $_SESSION['user_id'];
        $usuarioModel->id = $id_alumno; 

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
            // Actualizar la asignación de Carreras
            if ($usuarioModel->actualizarCarreras($id_alumno, $carreras_seleccionadas)) {
                  $_SESSION['info'] = "Perfil y asignación de carreras actualizados con éxito.";
            } else {
                $_SESSION['warning'] = "Perfil actualizado, pero **falló** la asignación de carreras.";
            }
        } else {
            $_SESSION['error'] = "Error al actualizar el perfil (DNI/Email duplicado).";
        }

        $_SESSION['mensaje'] = $mensaje;
        header("Location: " . '/alumno/vistaEditarPerfil');
        exit();
    }

    public function vistaCrearUsuarioAlumno() {
        $db = (new Database())->connect();
        $localidadModel = new Localidad($db); 
        $carreraModel = new Carrera($db); // Necesario para el listado total

        // Obtener la lista de TODAS las localidades
        $localidades = $localidadModel->obtenerTodas();

        // Obtener la lista de TODAS las carreras
        $carreras_totales = $carreraModel->obtenerTodas(); 
        
        require_once ROOT_PATH . 'app/views/alumno/crear_usuario.php';
    }

    public function crearUsuarioAlumno() {
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        // Generar Fechas
        $fecha_actual = date("Y-m-d");
        //Capturar las Carreras Seleccionadas
        // FILTER_REQUIRE_ARRAY asegura que recibamos un array o null si no se seleccionó nada
        $carreras_seleccionadas = filter_input(INPUT_POST, 'carreras', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($carreras_seleccionadas === null) {
            $carreras_seleccionadas = [];
        }

        // 3. Asignar propiedades al Modelo (usando tu código)
        $usuarioModel->dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_NUMBER_INT);
        $usuarioModel->nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $usuarioModel->usuario_name = filter_input(INPUT_POST, 'usuario_name', FILTER_SANITIZE_SPECIAL_CHARS); 
        $usuarioModel->password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT); 
        $usuarioModel->calle = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->id_localidad = filter_input(INPUT_POST, 'id_localidad', FILTER_SANITIZE_NUMBER_INT);
        $usuarioModel->fecha_inicio = $fecha_actual;
        $usuarioModel->id_rol = 'A'; 

        // 4. Crear en la base de datos
        if ($usuarioModel->crear()) {

            $nuevo_alumno_id = $db->lastInsertId(); // OBTENER EL ID DEL PROFESOR CREADO
        
            // 5. Asignar Carreras
            if ($usuarioModel->asignarCarreras($nuevo_alumno_id, $carreras_seleccionadas)) {
                $_SESSION['mensaje'] = "Alumno creado y asignado a carreras correctamente.";
                header("Location: /home/login");
            } else {
                 // Falló la asignación de carreras
                $_SESSION['warning'] = "Alumno creado, pero **FALLÓ** la asignación a carreras. Contacte al administrador.";
                header("Location: " . '/alumno/vistaCrearUsuarioAlumno');
            }
        
        } else {
            $_SESSION['error'] = "Error al crear el alumno. El DNI ya existen.";
            header("Location: " . '/alumno/vistaCrearUsuarioAlumno');
        }
        exit();
    }

}