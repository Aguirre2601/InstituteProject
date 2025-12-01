<?php
//Definir variables específicas para el header
$pageTitle = "Crear Nuevo Usuario Profesor";
require_once ROOT_PATH . 'app/views/layouts/header.php';
?>
<?php require_once ROOT_PATH . 'app/views/partials/session_messages_toast.php'; ?>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="h3 mb-0 text-primary">Crear Nuevo Usuario Profesor</h1>
        
        <a href="/director/dashboard" class="btn btn-m">
            Volver al Dashboard
        </a>
    </div>

    <form action="/director/crearProfesor" method="POST">
        
        <div class="row g-4">
            
            <div class="col-md-6">
                
                <fieldset class="border p-4 shadow-sm mb-4">
                    <legend class="float-none w-auto px-2 fs-5 text-primary">Datos Personales</legend>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="number" id="dni" name="dni" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email (Destinatario de credenciales):</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </fieldset>
                
                <fieldset class="border p-4 shadow-sm">
                    <legend class="float-none w-auto px-2 fs-5 text-primary">Dirección</legend>

                    <div class="mb-3">
                        <label for="id_localidad" class="form-label">Localidad:</label>
                        <select id="id_localidad" name="id_localidad" class="form-select" required>
                            <option value="">Seleccione Localidad</option>
                            <?php 
                            if (isset($localidades) && is_array($localidades)):
                                foreach ($localidades as $localidad): 
                            ?>
                                <option value="<?= $localidad->id ?>">
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
                        <input type="text" id="calle" name="calle" class="form-control">
                    </div>
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset class="border p-4 shadow-sm h-100">
                    <legend class="float-none w-auto px-2 fs-5 text-info">Carreras a Asignar</legend>
                    
                    <label class="form-label d-block mb-3 text-muted">Seleccione las Carreras (Puede seleccionar varias):</label>
                    
                    <div class="p-3 border rounded bg-white" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        $carreras_a_listar = $carreras ?? []; 
                        
                        foreach ($carreras_a_listar as $carrera): 
                        ?>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="carreras[]" 
                                    value="<?= $carrera->id ?>" 
                                    id="carrera_<?= $carrera->id ?>"
                                >
                                <label class="form-check-label" for="carrera_<?= $carrera->id ?>">
                                    <?= $carrera->descripcion ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($carreras_a_listar)): ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                No hay carreras registradas. No se podrá asignar el profesor.
                            </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
            </div>
            
            <div class="col-12 text-center mt-4">
                
                <button type="submit" class="btn  btn-lg">
                    Crear Profesor y Enviar Credenciales
                </button>
            </div>

        </div>
    </form>
    
</div>

<?php
require_once ROOT_PATH . 'app/views/layouts/footer.php';
?>
