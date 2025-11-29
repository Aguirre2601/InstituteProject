<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Director</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        h1 { color: #007bff; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #218838;
        }
        .note {
            margin-top: 15px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 4px;
        }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>⚙️ Editar Mi Perfil</h1>

    <?php 
    // Muestra errores de validación si existen (esto lo manejaría el Controller)
    if (isset($_SESSION['error'])): 
    ?>
        <p class="error"><?= $_SESSION['error'] ?></p>
    <?php 
        unset($_SESSION['error']); 
    endif; 
    ?>

    <p><a href="/director/dashboard">⬅️ Volver al Dashboard</a></p>

    <form action="/director/actualizarPerfil" method="POST">
        
        <input type="hidden" name="id_usuario" value="<?= $usuario->id ?? '' ?>">

        <fieldset>
            <legend>Información Personal</legend>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="<?= $usuario->dni ?? '' ?>" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= $usuario->nombre ?? '' ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?= $usuario->apellido ?? '' ?>" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?= $usuario->telefono ?? '' ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $usuario->email ?? '' ?>" required>

            <label for="email">Nombre de usuario:</label>
            <input type="text" id="usuario_name" name="usuario_name" value="<?= $usuario->usuario_name ?? '' ?>" required>

        </fieldset>

        <fieldset>
            <legend>Dirección</legend>

            <label for="id_localidad">Localidad:</label>
            <select id="id_localidad" name="id_localidad" required>
                <option value="">Seleccione su Localidad</option>
                <?php 
                // Asegúrate de que la variable $localidades esté definida
                if (isset($localidades) && is_array($localidades)):
                    foreach ($localidades as $localidad): 
                        // Marca la localidad actual del usuario como seleccionada
                        $selected = ($localidad->id == ($usuario->id_localidad ?? 0)) ? 'selected' : '';
                ?>
                    <option value="<?= $localidad->id ?>" <?= $selected ?>>
                        <?= $localidad->descripcion ?>
                    </option>
                <?php 
                    endforeach;
                endif;
                ?>
            </select>
            
            <label for="calle">Calle y Número:</label>
            <input type="text" id="calle" name="calle" value="<?= $usuario->calle ?? '' ?>">
            
        </fieldset>

        <fieldset>
            <legend>Cambiar Contraseña (Opcional)</legend>
            <div class="note">
                Solo complete estos campos si desea cambiar su contraseña.
            </div>

            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Dejar vacío para no cambiar">

            <label for="confirmar_password">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_password" name="confirmar_password">
        </fieldset>

        <button type="submit">💾 Guardar Cambios</button>
    </form>
</div>

</body>
</html>