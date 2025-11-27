<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Profesor</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Crear Nuevo Usuario Profesor 🧑‍🏫</h1>

    <?php 
    // Mostrar mensajes de sesión de error si existen (ej. después de un intento fallido)
    if (isset($_SESSION['mensaje'])): 
    ?>
        <p class="error"><?= $_SESSION['mensaje'] ?></p>
    <?php 
        unset($_SESSION['mensaje']); // Limpiamos el mensaje
    endif; 
    ?>

    <form action="/director/crearProfesor" method="POST">
        
        <h3>Datos Personales</h3>
        <div>
            <label for="dni">DNI:</label>
            <input type="number" id="dni" name="dni" required>
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>
        <div>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">
        </div>
        <div>
            <label for="email">Email (Será el destinatario de las credenciales):</label>
            <input type="email" id="email" name="email" required>
        </div>

        <h3>Dirección</h3>
        <div>
            <label for="calle">Calle y Número:</label>
            <input type="text" id="calle" name="calle">
        </div>
        <div>
            <label for="id_localidad">Localidad:</label>
            <select id="id_localidad" name="id_localidad" required>
                <option value="">Seleccione Localidad</option>
                <?php 
                // La variable $localidades viene del DirectorController::vistaCrearProfesor()
                foreach ($localidades as $localidad): 
                ?>
                    <option value="<?= $localidad->id ?>">
                        <?= $localidad->descripcion ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <h3>Carreras a Asignar</h3>
        <div>
            <label>Seleccione las Carreras (Puede seleccionar varias):</label>
            <div style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: scroll; background-color: #f9f9f9;">
                <?php 
                // La variable $carreras viene del DirectorController
                foreach ($carreras as $carrera): 
                ?>
                    <label style="display: block; font-weight: normal; margin-bottom: 5px;">
                        <input type="checkbox" name="carreras[]" value="<?= $carrera->id ?>">
                        <?= $carrera->descripcion ?>
                    </label>
                <?php endforeach; ?>
                <?php if (empty($carreras)): ?>
                    <p>⚠️ No hay carreras registradas. No se podrá asignar el profesor.</p>
                <?php endif; ?>
            </div>
        </div>
        <br>
        <button type="submit">Crear Profesor y Enviar Credenciales</button>
    </form>
    
    <br>
    <a href="/director/dashboard">← Volver al Dashboard</a>
</body>
</html>