<?php
// app/Middleware/CheckRoleMiddleware.php
class CheckRoleMiddleware
{
    public function handle(string $rolEsperado)
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $rolActual = $_SESSION['rol'] ?? ''; // Cambié 'id_rol' por 'rol'
        
        // DEBUGGING - SOLO EN DESARROLLO
        if (isset($_GET['debug'])) {
            echo "<h1>Debug del Middleware:</h1>";
            echo "Rol Requerido: [" . $rolEsperado . "]<br>";
            echo "Rol Actual en Session: [" . $rolActual . "]<br>";
            echo "User ID en Session: [" . ($_SESSION['user_id'] ?? 'NO') . "]<br>";
            if ($rolActual === $rolEsperado) {
                echo "<p style='color:green;'>¡COINCIDENCIA ENCONTRADA! El middleware PASA.</p>";
            } else {
                echo "<p style='color:red;'>¡FALLÓ EL CHECK! El middleware FALLA.</p>";
            }
        }
        
        // Verificar sesión y rol
        if (!isset($_SESSION['user_id']) || $rolActual !== $rolEsperado) {
            $this->accesoDenegado();
            return false; 
        }
        
        return true; 
    }

    public function accesoDenegado() {
        http_response_code(403);
        require_once ROOT_PATH . 'app/views/error/acceso_denegado.php'; 
        exit();
    }
}
?>