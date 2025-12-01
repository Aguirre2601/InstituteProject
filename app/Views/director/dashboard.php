<?php
//Definir variables específicas para el header
$pageTitle = "Dashboard Director";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>
<?php  require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        
        <h1 class="h3 mb-0 text-primary">
            Bienvenido Director/a, <?= ($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? '') ?>
        </h1>
        
        <div>
            <a href="/director/vistaCrearProfesor" class="btn btn-m me-2">
                Crear Nuevo Profesor
            </a>
            <a href="/director/vistaEditarPerfil" class="btn btn-m me-2">
                Editar Perfil
            </a>
            <a href="/auth/logout" class="btn btn-m ">
                Cerrar Sesión
            </a>
        </div>
    </div>
    
    <p class="text-muted mb-4">Desde aquí puede gestionar Profesores y ver Alumnos.</p>

    <h2 class="mt-5 mb-3"> Listado de Profesores Activos 
        <span class="badge bg-success"><?= count($profesores) ?></span>
    </h2>
    
    <div class="row mb-4 g-3 align-items-center bg-light p-3 rounded shadow-sm">
        <div class="col-md-auto">
            <strong class="text-secondary d-block"> Filtros:</strong>
        </div>
        <div class="col-md-4">
            <select id="filtroCarreraProfesor" class="form-select">
                <option value="">Filtrar por Carrera</option>
                <?php foreach ($carreras_filtro as $carrera): ?>
                    <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" id="filtroProfesor" class="form-control" placeholder="Buscar Profesor por Nombre o DNI...">
        </div>
    </div>
    
    <?php if (empty($profesores)): ?>
        <div class="alert alert-info mt-4" role="alert">
            No hay profesores registrados.
        </div>
    <?php else: ?>
    <div class="table-responsive" id="tablaContainer">
        <table id="tablaProfesores" class="table table-striped table-hover align-middle">
            <!-- Tu contenido actual SIN modificaciones -->
            <thead class="table-secondary">
                <tr>
                    <th>DNI</th>
                    <th>Nombre y Apellido</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Localidad</th>
                    <th>Calle</th>
                    <th>Fecha Inicio</th>
                    <th>Carrera(s) Asignada(s)</th>
                    <th class="text-center">Acciones</th>
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
                        <span class="text-dark"><?= $usuario->carreras_nombre ?></span>
                    </td>
                    <td class="text-center">
                        <a href="/director/darDeBajaProfesor/<?= $usuario->id ?>" 
                            class="btn btn-sm "
                            onclick="return confirm('¿Está seguro de dar de baja al Profesor <?= $usuario->apellido ?>? Esta acción no se puede deshacer.');">
                            🗑️ Dar de Baja
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
                
        <div id="sinResultadosP" class="alert alert-danger text-center mt-3" style="display:none;" role="alert">
            <strong>¡Atención!</strong> No se encontraron profesores con esos filtros aplicados.
        </div>
    <?php endif; ?>


    <hr class="mt-5 mb-5">
    
    <h2 class="mb-3"> Listado de Alumnos Activos 
        <span class="badge bg-success"><?= count($alumnos) ?></span>
    </h2>
    
    <div class="row mb-4 g-3 align-items-center bg-light p-3 rounded shadow-sm">
        <div class="col-md-auto">
            <strong class="text-secondary d-block"> Filtros:</strong>
        </div>
        <div class="col-md-4">
            <select id="filtroCarrera" class="form-select"> 
                <option value="">Filtrar por Carrera</option>
                <?php foreach ($carreras_filtro as $carrera): ?>
                    <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" id="filtroNombreAlumno" class="form-control" placeholder="Buscar Alumno por Nombre o DNI...">
        </div>
    </div>
    
    <?php if (empty($alumnos)): ?>
        <div class="alert alert-info mt-4" role="alert">
            No hay alumnos registrados.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table id="tablaAlumnos" class="table table-striped table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>DNI</th>
                        <th>Nombre y Apellido</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Localidad</th>
                        <th>Calle</th>
                        <th>Carreras</th>
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
                        <td>
                            <span class="text-dark"><?= $usuario->carreras_nombre ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div id="sinResultadosA" class="alert alert-danger text-center mt-3" style="display:none;" role="alert">
            <strong>¡Atención!</strong> No se encontraron alumnos con esos filtros aplicados.
        </div>
    <?php endif; ?>

</div>

<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
