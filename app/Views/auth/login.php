<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login de Usuarios</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <form action="/auth/iniciar" method="POST">
        <div>
            <label for="email">Email o Usuario:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit">Ingresar</button>
    </form>
    
    <hr>
    
    <p>¿Aún no tienes cuenta? 
        <a href="/alumno/vistaCrearUsuarioAlumno">
            <button type="button">Crear Usuario</button>
        </a>
    </p>

</body>
<?php 
if (isset($_SESSION['mensaje'])): 
?>
    <p style="color: red;"><?= $_SESSION['mensaje'] ?></p>
<?php 
    unset($_SESSION['mensaje']); // Limpiamos el mensaje después de mostrarlo
endif; 
?>
</html>