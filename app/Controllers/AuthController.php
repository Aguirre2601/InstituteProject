<?php
// app/Controllers/AuthController.php
class AuthController {

    public static function redirectToDashboard($rol) {
        // Mantenemos esta función de redirección aquí, ya que es lógica de autenticación.
        // La hemos movido del HomeController para centralizarla.
        switch($rol) {
            case 'A': // Alumno
                header("Location: /alumno/vistaEditarPerfil"); 
                break;
            case 'D': // Director directorController y su metodo dashnoard()
                header("Location: /director/dashboard");
                break;
            case 'P': // Profesor
                header("Location:/profesor/dashboard");
                break;
            default:
                session_destroy();
                header("Location: /home/login");
        }
        exit();
    }

    // Método que se llama cuando el formulario de login hace POST
    public function iniciar() {
        // 1. Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /home/login");
            exit();
        }

        // 2. Conexión y Modelo
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);

        // 3. Obtener datos del formulario
        $email_o_user = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password_ingresada = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        
        // 4. Buscar usuario por email/usuario_name (Usando el método del modelo)
        $usuario_data = $usuarioModel->buscarPorEmail($email_o_user);
        if ($usuario_data) {
            // 5. Verificar la Contraseña Hasheada
            if (password_verify($password_ingresada, $usuario_data->password)) {
                
                // 6. Verificar si el usuario está ACTIVO
                if ($usuario_data->activo == 0) {
                    $_SESSION['warning'] = "Su cuenta ha sido dada de baja.";
                    header("Location: /home/login");
                    exit();
                }

                // 7. Éxito: Crear la Sesión
                $_SESSION['user_id'] = $usuario_data->id;
                $_SESSION['nombre'] = $usuario_data->nombre;
                $_SESSION['apellido'] = $usuario_data->apellido;
                $_SESSION['rol'] = $usuario_data->id_rol; // Clave para la redirección

                //echo "Rol actual establecido: " . $_SESSION['rol']; exit();

                // Redirigir al Dashboard correcto
                $this->redirectToDashboard($usuario_data->id_rol); 


            } else {
                //Error de Contraseña
                $_SESSION['error'] = "Contraseña incorrecta.";
                header("Location: /home/login");
            }

        } else {
            //Error: Usuario no encontrado
            $_SESSION['warning'] = "Usuario no encontrado.";
            header("Location: /home/login");
        }
    }

    // Función para cerrar la sesión
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /home/login");
        exit();
    }

    public function crearUsuario() {
        header("Location: /alumno/vistaCrearUsuarioAlumno");
        exit();
    }

    public function vistaRecuperaContrasenia() {
        require_once ROOT_PATH . 'app/views/auth/recuperaContrasenia.php';
    }

    public function recuperaContrasenia() {
        $db = (new Database())->connect();
        $usuarioModel = new Usuario($db);
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);// Obtener datos del formulario
        $password_generada = substr(md5(uniqid(rand(), true)), 0, 8);
        $usuario_name_generado = 'user_' . substr(md5(uniqid(rand(), true)), 0, 5);
        
        // Buscar usuario por email/usuario_name (Usando el método del modelo)
        $usuario_data = $usuarioModel->buscarPorEmail($email);
        if ($usuario_data) {
                $apellido = $usuario_data->apellido;
                $usuarioModel->actualizarCredencialesPorEmail($email, $usuario_name_generado, $password_generada);
                $this->enviarEmailRecuperacion($email,$apellido, $password_generada, $usuario_name_generado);

                $_SESSION['mensaje'] = "Se ha enviado un usuario y contraseña aleatorios a su correo electrónico.";
                header("Location: /home/login");
        }else {
            $_SESSION['error'] = "Usuario no encontrado o dado de baja. Contacte al administrador.";
            header("Location: /auth/vistaRecuperaContrasenia");
        }
    }
    private function enviarEmailRecuperacion($destinatario, $apellido, $password, $usuario_name) {
    // Instanciar el servicio de correo
        $mailer = new \App\Services\Mailer();

        // Intentar enviar el email
        if ($mailer->recuperarCredenciales($destinatario, $apellido, $usuario_name, $password)) {
            // Éxito:
            $_SESSION['mensaje'] = "Credenciales enviadas por email.";
        } else {
            // Fallo en el envío del email:
            $_SESSION['warning'] = "Falló el envío del email con las credenciales.";
        }
    }

}