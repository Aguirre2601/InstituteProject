<?php
//Definir variables específicas para el header
$pageTitle = "Recuperar Contraseña";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>

<?php require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>
<div  class="d-flex justify-content-end align-items-end">
    <a href="/" class="btn btn-lg ms-3"> Cancelar </a>
</div>
<div class="d-flex justify-content-center align-items-center vh-auto" style="min-height: 80vh;">
    <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-center">
            
            <h3 class="card-title text-primary mb-4">Recuperar Contraseña</h3>

            <form action="/auth/recuperaContrasenia" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label d-flex justify-content-start">Email o Usuario:</label>
                    <input type="text" id="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Enviar 
                </button>
            </form>
        </div>
    </div>
</div>


<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
