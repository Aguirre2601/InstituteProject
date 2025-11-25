<?php include("conexion.php"); ?>
<?php include("includes/header.php"); ?>

<nav class="container">
    <form action="" method="post"> 
        <div class="mb-3">
            <label class="form-label">Usuario: </label>
            <input type="text" class="form-control" name="userName" placeholder="ej.: Alumno123..." required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña: </label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
        <ul>
            <li>Crear Usuario</li>
            <li>Recuperar contraseña</li>
        </ul>
    </form>
</nav>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['userName'];
    $password = $_POST['password']; // Contraseña ingresada por el usuario

    $query = "SELECT password, id_rol, id FROM usuario WHERE usuario_name = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($resultado) === 1) {
        $fila = mysqli_fetch_assoc($resultado);
        $hash_almacenado = $fila['password']; // Contraseña cifrada de la BD
        $rol_obtenido_de_bd = $fila['id_rol']; // Rol (ej: 'D', 'P', 'A')

        // password_verify() compara la contraseña ingresada con el hash
        if (password_verify($password, $hash_almacenado)) {
            
            // ÉXITO: Usuario Autenticado
            $_SESSION['logueado'] = true;
            $_SESSION['rol'] = $rol_obtenido_de_bd; 
            $_SESSION['user_id'] = $fila['id'];

            switch ($_SESSION['rol']) {
                case 'D':
                    header("Location: Roles/Director/index.php");
                    break;
                case 'P':
                    header("Location: Roles/Profesor/index.php");
                    break;
                case 'A':
                    header("Location: Roles/Alumno/index.php");
                    break;
                default:
                    // Rol desconocido
                    session_destroy();
                    header("Location: login.php?error=rol_desconocido");
                    break;
            }
            exit(); // Detiene la ejecución

        } else {
            // FALLO: Contraseña incorrecta
            $error_login = "Usuario o contraseña incorrectos.";
        }

    } else {
        // FALLO: Usuario no encontrado
        $error_login = "Usuario o contraseña incorrectos.";
    }

    mysqli_stmt_close($stmt);

} 

// ----------------------------------------------------
// MOSTRAR ERRORES EN EL FORMULARIO
// Si existe $error_login, lo mostramos al usuario
if (isset($error_login)) {
    echo '<div class="alert alert-danger container mt-3" role="alert">' . $error_login . '</div>';
}
?>

<?php include("includes/footer.php"); ?>