<?php
//app/Controllers/DirectorController.php
class DirectorController {
    // URL: /director/dashboard
    public function dashboard() {
        // 1. Conexión y Modelo
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        $carreraModel = new Carrera($db);
        // a) Listar Profesores
        $stmt_profesores = $usuarioModel->listarProfesores(); 
        $profesores = $stmt_profesores->fetchAll(PDO::FETCH_OBJ); // <- Variable $profesores
        
        // b) Listar Alumnos
        $stmt_alumnos = $usuarioModel->listarAlumnos(); 
        $alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_OBJ); // <- Variable $alumnos

        // 3. OBTENER CARRERAS (Para el filtro combobox)
        $carreras_filtro = $carreraModel->obtenerTodas(); 
        // 3. Cargamos la vista principal del director
        // La vista dashboard.php ahora espera DOS variables: $profesores y $alumnos
        require_once ROOT_PATH . 'app/views/director/dashboard.php';
    }

    
    // URL: /director/darDeBajaProfesor/5 (El ID viene en $id)
    public function darDeBajaProfesor($id) {
        if (!$this->isDirector() || !is_numeric($id)) return;

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        // 1. Buscamos el usuario para verificar que sea PROFESOR ('P')
        $usuario = $usuarioModel->leerUno($id); // Este método lo agregaremos al Modelo
        
        if ($usuario && $usuario->id_rol === 'P') {
            // 2. Asignamos el ID al objeto del modelo para la baja
            $usuarioModel->id = $id;
            
            if ($usuarioModel->darBaja()) {
                $_SESSION['mensaje'] = "Profesor dado de baja lógicamente con éxito.";
            } else {
                $_SESSION['mensaje'] = "Error al dar de baja al profesor.";
            }
        } else {
            $_SESSION['mensaje'] = "Error: El usuario no existe o no es un Profesor.";
        }

        // Redireccionar al listado
        header("Location: " . '/director/dashboard');
        exit();
    }

    // URL: /director/vistaCrearProfesor (GET para mostrar el formulario)
    public function vistaCrearProfesor() {
        $db = (new Database())->connect();
        // Necesitamos las localidades para el <select> del formulario
        $localidadModel = new Localidad($db);
        $localidades = $localidadModel->obtenerTodas();
        // Obtener Carreras 
        $carreraModel = new Carrera($db);
        $carreras = $carreraModel->obtenerTodas();
        
        require_once ROOT_PATH . 'app/views/director/profesor_crear.php';
    }

    // URL: /director/crearProfesor (POST para procesar el formulario)
    public function crearProfesor() {
        if (!$this->isDirector() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /director/dashboard");
            exit();
        }

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        // 1. Generar Contraseña, Usuario Name, y Fechas
        $password_generada = substr(md5(uniqid(rand(), true)), 0, 8);
        $usuario_name_generado = 'profe_' . substr(md5(uniqid(rand(), true)), 0, 5);
        $fecha_actual = date("Y-m-d");

        // 2. Capturar las Carreras Seleccionadas
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
        $usuarioModel->calle = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuarioModel->id_localidad = filter_input(INPUT_POST, 'id_localidad', FILTER_SANITIZE_NUMBER_INT);
        $usuarioModel->fecha_inicio = $fecha_actual;
        $usuarioModel->password = $password_generada;
        $usuarioModel->usuario_name = $usuario_name_generado;
        $usuarioModel->id_rol = 'P'; // Rol fijo para Profesor

        // 4. Crear en la base de datos
        if ($usuarioModel->crear()) {

            $nuevo_profesor_id = $db->lastInsertId(); // OBTENER EL ID DEL PROFESOR CREADO
        
            // 5. Asignar Carreras
            if ($usuarioModel->asignarCarreras($nuevo_profesor_id, $carreras_seleccionadas)) {

                // 6. Enviar email (Notificación)
                $this->enviarEmailProfesor($usuarioModel->email, $password_generada, $usuario_name_generado);

                $_SESSION['mensaje'] = "Profesor creado y asignado a carreras con éxito. Se envió un email.";
                header("Location: " . '/director/dashboard');
            } else {
                 // Falló la asignación de carreras
                $_SESSION['mensaje'] = "Profesor creado, pero **FALLÓ** la asignación a carreras. Contacte al administrador.";
                header("Location: " . '/director/dashboard');
            }
        
        } else {
            $_SESSION['mensaje'] = "Error al crear el profesor. El DNI o Email ya existen.";
            header("Location: " . '/director/vistaCrearProfesor');
        }
        exit();
    }

    // Función de envío de email (Notificación)
    private function enviarEmailProfesor($destinatario, $password, $usuario_name) {
        $asunto = "Credenciales de Acceso - Instituto 93";
        $mensaje = "Hola,\n\nSus credenciales de acceso son:\n";
        $mensaje .= "Usuario: " . $usuario_name . "\n";
        $mensaje .= "Contraseña: " . $password . "\n\n";
        $mensaje .= "Por favor, cambie su contraseña al iniciar sesión.\n";
        $headers = 'From: noreply@instituto93.com' . "\r\n" .
                   'Reply-To: noreply@instituto93.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // ⚠️ ATENCIÓN: La función mail() de PHP necesita un servidor de correo local 
        // configurado (ej: Mercury o un servicio SMTP externo).
        // Si no tienes configurado un servidor, esta línea NO funcionará, pero es la implementación correcta:
        // mail($destinatario, $asunto, $mensaje, $headers); 
        
        // Por ahora, simularemos que se envió y mostraremos un mensaje
        // (En un entorno de producción, esto SÍ debe funcionar)
        error_log("EMAIL SIMULADO ENVIADO a {$destinatario}: User={$usuario_name}, Pass={$password}");
    }

    // URL: /director/vistaEditarPerfil (Muestra el formulario - MÉTODO GET)
    public function vistaEditarPerfil() {
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        $localidadModel = new Localidad($db); 

        $id_director = $_SESSION['user_id'];

        // 1. Obtener los datos actuales del perfil del profesor
        $director = $usuarioModel->leerUno($id_director); 

        // 2. Obtener la lista de TODAS las localidades
        $localidades = $localidadModel->obtenerTodas();

        if (!$director) {
            $_SESSION['mensaje'] = "Error: No se encontró el perfil para editar.";
            header("Location: /director/dashboard");
            exit();
        }

        require_once ROOT_PATH . 'app/views/director/perfil_editar.php';
    }

    // URL: /director/actualizarPerfil (Procesa el formulario - MÉTODO POST)
    public function actualizarPerfil() {
        if (!$this->isDirector() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /director/dashboard");
            exit();
        }

        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        $id_director = $_SESSION['user_id'];
        $usuarioModel->id = $id_director; 

        // 1. Asignar datos personales al Modelo
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
                $mensaje = "Perfil actualizado con éxito.";
            } else {
                $mensaje = "Error al actualizar el perfil.";
            }

        $_SESSION['mensaje'] = $mensaje;
        header("Location: " . '/director/dashboard');
        exit();
    }
}