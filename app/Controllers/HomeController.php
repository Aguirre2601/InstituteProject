<?php
// app/Controllers/HomeController.php
class HomeController {

    public function index() {
        // Verificar si ya está logueado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if(isset($_SESSION['user_id']) && isset($_SESSION['rol'])) {
            // Redirigir al dashboard correspondiente
            AuthController::redirectToDashboard($_SESSION['rol']);
        } else {
            // Si no hay sesión, mostrar login
            $this->login();
        }
    }

    public function login() {
        // Mostrar vista de login
        require_once ROOT_PATH . 'app/views/auth/login.php'; 
    }
}
?>