<?php
// Asegúrate de que 'conexion.php' esté bien configurado
include("conexion.php"); 

// --- Configuración de Seguridad ---
// Deshabilitar el límite de tiempo para scripts largos (si hay muchos usuarios)
set_time_limit(0); 

echo "Iniciando proceso de hasheo de contraseñas...\n";

// 1. Obtener todos los usuarios que tengan la contraseña en texto plano
// Es crucial que aquí selecciones la contraseña que está en texto plano.
$query_select = "SELECT id, password FROM usuario WHERE 1"; 
$resultado = mysqli_query($conn, $query_select);

if (mysqli_num_rows($resultado) > 0) {
    
    // Preparar la sentencia de actualización fuera del bucle para eficiencia
    $stmt = mysqli_prepare($conn, "UPDATE usuario SET password = ? WHERE id = ?");

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $id_usuario = $fila['id'];
        $password_plano = $fila['password']; // Asumimos que aquí está el texto plano
        
        // Generar el hash seguro
        $hash_seguro = password_hash($password_plano, PASSWORD_DEFAULT);

        // Actualizar el registro en la base de datos con el hash
        mysqli_stmt_bind_param($stmt, "si", $hash_seguro, $id_usuario); // 's' para string (hash), 'i' para integer (id)
        mysqli_stmt_execute($stmt);

        echo "Usuario ID: $id_usuario hasheado con éxito.\n";
    }

    mysqli_stmt_close($stmt);
    echo "¡Proceso de hasheo finalizado con éxito!\n";

} else {
    echo "No se encontraron usuarios para hashear.\n";
}

mysqli_close($conn);
?>