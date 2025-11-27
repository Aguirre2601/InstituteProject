<!DOCTYPE html>
<html lang="es">
<body>

    <h1>Bienvenido Director/a, <?= ($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? '') ?></h1>
    <p>Desde aquí puede gestionar Profesores y ver Alumnos.</p>

    <p><a href="/director/vistaCrearProfesor">➕ Crear Nuevo Profesor</a></p>
    <p><a href="/auth/logout">🚪 Cerrar Sesión</a></p>

    <hr>

    <?php 
    // Mensajes de sesión
    if (isset($_SESSION['mensaje'])): 
    ?>
        <p class="success"><?= $_SESSION['mensaje'] ?></p>
    <?php 
        unset($_SESSION['mensaje']); 
    endif; 
    ?>

    <h2>🧑‍🏫 Listado de Profesores Activos (<?= count($profesores) ?>)</h2>
    
    <table>
        <thead>
            <tr>
                <!--<th>ID</th>-->
                <th>DNI</th>
                <th>Nombre y Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Localidad</th>
                <th>Calle</th>
                <th>Fecha de Inicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profesores as $usuario): ?>
            <tr>
                <td><?= $usuario->dni ?></td>
                <td><?= $usuario->nombre . ' ' . $usuario->apellido ?></td>
                <td><?= $usuario->telefono ?></td>
                <td><?= $usuario->email ?></td>
                <td><?= $usuario->localidad_nombre ?></td>
                <td><?= $usuario->calle ?></td>
                <td><?= $usuario->fecha_inicio ?></td>
                <td>
                    <a href="/director/darDeBajaProfesor/<?= $usuario->id ?>" 
                       onclick="return confirm('¿Está seguro de dar de baja al Profesor <?= $usuario->apellido ?>?');">
                       Dar de Baja
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><hr><br>

    <h2>🧑‍🎓 Listado de Alumnos Activos (<?= count($alumnos) ?>)</h2>
    
    <table>
        <thead>
            <tr>
                <!--<th>ID</th>-->
                <th>DNI</th>
                <th>Nombre y Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Localidad</th>
                <th>Calle</th>
                <th>Fecha de Inicio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $usuario): ?>
            <tr>
                <td><?= $usuario->dni ?></td>
                <td><?= $usuario->nombre . ' ' . $usuario->apellido ?></td>
                <td><?= $usuario->telefono ?></td>
                <td><?= $usuario->email ?></td>
                <td><?= $usuario->localidad_nombre ?></td>
                <td><?= $usuario->calle ?></td>
                <td><?= $usuario->fecha_inicio ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>