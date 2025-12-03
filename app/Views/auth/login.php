<?php
//Definir variables específicas para el header
$pageTitle = "Login de usuario";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>

<?php require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>

<div class="d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-center">
            
            <h3 class="card-title text-primary mb-4">Iniciar Sesión</h3>

            <form action="/auth/iniciar" method="POST">
                
                <div class="mb-3">
                    <label for="email" class="form-label d-flex justify-content-start">Email o Usuario:</label>
                    <input type="text" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label d-flex justify-content-start">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <div class="text-end"> 
                        <a href="/auth/vistaRecuperaContrasenia" class="mt-1 fs-6"> ¿Olvidó su contraseña?</a> 
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Ingresar
                </button>
            </form>
            
            <hr>
            
            <p class="mt-3 text-muted">¿Aún no tienes cuenta? </p>
            
            <a href="/alumno/vistaCrearUsuarioAlumno" class="btn btn-outline-secondary w-100">
                Crear Usuario
            </a>
            
        </div>
    </div>
</div>


<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
