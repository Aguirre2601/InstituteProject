<?php
//Definir variables específicas para el header
$pageTitle = "Crear Usuario Alumno";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>

<?php require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>

    <div class="container py-5">
    <div  class="d-flex justify-content-end align-items-end">
        <a href="/" class="btn btn-lg ms-3"> Cancelar </a>
    </div>
    <h1 class="mb-4 border-bottom pb-2">Crear Nuevo Usuario</h1>

    <form action="/alumno/crearUsuarioAlumno" method="POST">
        <div class="row g-3">
            
            <div class="col-md-6">
                <fieldset class="border p-4 shadow-sm mb-4">
                    <legend class="float-none w-auto px-1 fs-5 text-primary">Información Personal</legend>

                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI:</label>
                        <input type="text" id="dni" name="dni" value="<?= $usuario->dni ?? '' ?>" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" value="<?= $usuario->nombre ?? '' ?>" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" value="<?= $usuario->apellido ?? '' ?>" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" value="<?= $usuario->telefono ?? '' ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" value="<?= $usuario->email ?? '' ?>" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="usuario_name" class="form-label">Nombre de usuario:</label>
                        <input type="text" id="usuario_name" name="usuario_name" value="<?= $usuario->usuario_name ?? '' ?>" class="form-control" required>
                    </div>

                </fieldset>
            </div>

            <div class="col-md-6">
                
                <fieldset class="border p-4 shadow-sm mb-4">
                    <legend class="float-none w-auto px-1 fs-5 text-primary">Dirección</legend>

                    <div class="mb-3">
                        <label for="id_localidad" class="form-label">Localidad:</label>
                        <select id="id_localidad" name="id_localidad" class="form-select" required>
                            <option value="">Seleccione su Localidad</option>
                            <?php 
                            if (isset($localidades) && is_array($localidades)):
                                foreach ($localidades as $localidad): 
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
                    </div>
                    
                    <div class="mb-3">
                        <label for="calle" class="form-label">Calle y Número:</label>
                        <input type="text" id="calle" name="calle" value="<?= $usuario->calle ?? '' ?>" class="form-control">
                    </div>
                </fieldset>
                
                <fieldset class="border p-4 shadow-sm mb-4">
                    <legend class="float-none w-auto px-1 fs-5 text-primary">Contraseña</legend>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Debe tener al menos 8 caracteres." class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_password" class="form-label">Confirmar Contraseña:</label>
                        <input type="password" id="confirmar_password" name="confirmar_password" class="form-control" required>
                    </div>
                </fieldset>
            </div>
            
            <div class="col-12">
                <fieldset class="border p-4 shadow-sm mb-4">
                    <legend class="float-none w-auto px-1 fs-5 text-info">Carreras a Asignar</legend>
                    
                    <label class="form-label d-block">Seleccione las Carreras (Puede seleccionar varias):</label>
                    
                    <div class="p-3 border rounded" style="max-height: 200px; overflow-y: auto;">
                        <?php 
                        foreach ($carreras_totales as $carrera): 
                        ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="carreras[]" value="<?= $carrera->id ?>" id="carrera_<?= $carrera->id ?>">
                                <label class="form-check-label" for="carrera_<?= $carrera->id ?>">
                                    <?= $carrera->descripcion ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($carreras_totales)): ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                ⚠️ No hay carreras registradas. No se podrá asignar el alumno.
                            </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
            </div>
            
            <div class="col-12 mt-4 text-center">
                <button type="submit" class="btn btn-lg">
                    <i class="fas fa-save"></i> 💾 Crear Usuario
                </button>
            </div>

        </div> </form>
</div>

<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
