<?php
//Definir variables específicas para el header
$pageTitle = "Dashboard Profesor";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>

<?php require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <h1 class="h3 mb-0 text-primary">
            Bienvenido Profesor/a, <?= $profesor->nombre . ' ' . $profesor->apellido ?>
        </h1>
        
        <div>
            <a href="/profesor/vistaEditarPerfil" class="btn btn-m me-2">
                Editar Mi Perfil
            </a>
            <a href="/auth/logout" class="btn  btn-m">
                Cerrar Sesión
            </a>
        </div>
    </div>
    
    <p class="text-muted small">Rol: Profesor | Usuario: <?= $profesor->usuario_name ?></p>
    
    <hr class="mb-4">

    <h2>Alumnos Asignados 
        <span class="badge text-bg-success"><?= count($alumnos) ?></span>
    </h2>
    
    <div class="row mb-4 g-3 align-items-center">
        <div class="col-md-auto">
            <strong class="text-secondary d-block mb-1"> Filtrar por:</strong>
        </div>

        <div class="col-md-4">
            <label for="filtroCarrera" class="visually-hidden">Filtrar por Carrera</label>
            <select id="filtroCarrera" class="form-select">
                <option value="">Todas las Carreras</option>
                <?php foreach ($carreras_filtro as $carrera): ?>
                    <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="filtroNombre" class="visually-hidden">Buscar por nombre o apellido</label>
            <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por DNI, nombre o apellido...">
        </div>
    </div>

    <?php if (empty($alumnos)): ?>
        <div class="alert alert-warning mt-4" role="alert">
            No hay alumnos asignados a su cuenta de profesor.
        </div>
    <?php else: ?>
    
    <div class="table-responsive">
        <table id="tablaAlumnos" class="table table-striped table-hover align-middle">
            <thead class=" table-secondary">
                <tr>
                    <th>DNI</th>
                    <th>Nombre y Apellido</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Localidad</th>
                    <th>Carrera</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                <tr> 
                    <td><?= $alumno->dni ?></td>
                    <td><?= $alumno->nombre . ' ' . $alumno->apellido ?></td>
                    <td><?= $alumno->telefono ?></td>
                    <td><?= $alumno->email ?></td>
                    <td><?= $alumno->localidad_nombre ?></td>
                    <td>
                        <span class=" text-dark"><?= $alumno->carrera_nombre ?></span>
                    </td>
                    <td class="text-center">
                        
                        <a href="/profesor/darDeBajaAlumno/<?= $alumno->id ?>/<?= $alumno->id_carrera ?>"
                            class="btn btn-sm "
                            onclick="return confirm('¿Está seguro de dar de baja al Alumno <?= $alumno->apellido ?> de la carrera <?= $alumno->carrera_nombre ?>? Esta acción no se puede deshacer.');">
                            🗑️ Dar de Baja
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div id="sinResultados" class="alert alert-danger text-center mt-3" style="display:none;" role="alert">
        <strong>¡Atención!</strong> No se encontraron alumnos con esos filtros aplicados.
    </div>

    <?php endif; ?>
</div>


<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
