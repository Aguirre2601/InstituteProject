<?php
class HomeController {

    // Este es el método que se llama por defecto cuando la URL es la raíz (/)
    public function index() {
        // Lógica para decidir si mostrar el login o el dashboard
        if(isset($_SESSION['user_id'])) {
            AuthController::redirectToDashboard($_SESSION['rol']);
        } else {
            // Si no hay sesión, mostrar la vista de Login
            $this->login();
        }
    }

    public function login() {
        // Simplemente cargamos la vista HTML del formulario de login
        // Asegúrate de crear este archivo en la carpeta views.
        require_once ROOT_PATH . 'app/views/auth/login.php'; 
    }

}