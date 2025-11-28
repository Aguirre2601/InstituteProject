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
    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <select id="filtroCarreraAlumno">
            <option value="">Filtrar por Carrera</option>
            <?php foreach ($carreras_filtro as $carrera): ?>
                <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
            <?php endforeach; ?>
        </select>
            
        <input type="text" id="filtroProfesor" placeholder="Buscar Profesor por Nombre o DNI..." style="flex-grow: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <table id="tablaProfesores" class="tabla-gestion">
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
                <th>Carrera</th>
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
                <td><?= $usuario->carreras_nombre ?></td>
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
    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
        <select id="filtroCarrera">
            <option value="">Filtrar por Carrera</option>
            <?php foreach ($carreras_filtro as $carrera): ?>
                <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
            <?php endforeach; ?>
        </select>
            
        <input type="text" id="filtroNombreAlumno" placeholder="Buscar Alumno por Nombre o DNI..." style="flex-grow: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <table id="tablaAlumnos" class="tabla-gestion">
        <thead>
            <tr>
                <!--<th>ID</th>-->
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
                <td><?= $usuario->carreras_nombre ?></td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ###########################################################
        // 1. Obtención de Elementos del DOM
        // ###########################################################

        // PROFESORES:
        // Nota: 'filtroCarreraAlumno' está mal nombrado en el HTML, pero asumo que es el filtro de Carrera para Profesores.
        const inputFiltroCarreraProfesor = document.getElementById('filtroCarreraAlumno'); 
        const inputFiltroProfesor = document.getElementById('filtroProfesor');           
        const tablaProfesores = document.getElementById('tablaProfesores');
        
        // ALUMNOS:
        const inputFiltroCarreraAlumno = document.getElementById('filtroCarrera');       
        const inputNombreAlumno = document.getElementById('filtroNombreAlumno');        
        const tablaAlumnos = document.getElementById('tablaAlumnos');

        // ###########################################################
        // 2. Función de Filtrado Genérica y Reutilizable
        // ###########################################################

        /**
         * Aplica filtros de texto/DNI y carrera a una tabla específica.
         * @param {HTMLElement} tablaElement - La tabla a filtrar.
         * @param {string} filtroTexto - Texto de búsqueda (nombre/dni).
         * @param {string} filtroCarrera - Valor seleccionado del combobox de carrera.
         * @param {number} idxDNI - Índice de la columna DNI.
         * @param {number} idxNombre - Índice de la columna Nombre/Apellido.
         * @param {number} idxCarrera - Índice de la columna Carreras.
         */
        function filtrarTabla(tablaElement, filtroTexto, filtroCarrera, idxDNI, idxNombre, idxCarrera) {
            if (!tablaElement) return;

            const filas = tablaElement.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < filas.length; i++) {
                // Obtenemos las celdas necesarias usando los índices proporcionados
                const celdaCarrera = filas[i].getElementsByTagName('td')[idxCarrera];
                const celdaDNI = filas[i].getElementsByTagName('td')[idxDNI];
                const celdaNombre = filas[i].getElementsByTagName('td')[idxNombre];
                
                let mostrar = true; // Por defecto mostramos la fila

                // --- 1. FILTRO POR CARRERA ---
                if (filtroCarrera !== "" && celdaCarrera) {
                    const textoCarrera = (celdaCarrera.textContent || celdaCarrera.innerText).toLowerCase();
                    
                    // Si el texto de la columna Carreras NO incluye la carrera seleccionada, ocultar.
                    if (!textoCarrera.includes(filtroCarrera)) {
                        mostrar = false;
                    }
                }

                // --- 2. FILTRO POR TEXTO (Nombre/DNI) ---
                if (mostrar && filtroTexto !== "") {
                    // Nota: Convertimos a minúsculas solo el campo de nombre.
                    const textoNombre = (celdaNombre.textContent || celdaNombre.innerText).toLowerCase();
                    const textoDNI = celdaDNI.textContent || celdaDNI.innerText; 
                    
                    // Debe coincidir el filtro en el Nombre O en el DNI
                    const coincideTexto = textoNombre.includes(filtroTexto) || 
                                          textoDNI.includes(filtroTexto);
                    
                    if (!coincideTexto) {
                        mostrar = false;
                    }
                }
                
                filas[i].style.display = mostrar ? "" : "none";
            }
        }


        // ###########################################################
        // 3. Lógica Específica para Profesores
        // ###########################################################
        function filtrarProfesores() {
            const filtroTexto = inputFiltroProfesor.value.toLowerCase();
            const filtroCarrera = inputFiltroCarreraProfesor.value.toLowerCase();
            
            // Índices de la tabla Profesores (DNI=0, Nombre=1, Carreras=7)
            filtrarTabla(tablaProfesores, filtroTexto, filtroCarrera, 0, 1, 7);
        }
        
        // ###########################################################
        // 4. Lógica Específica para Alumnos
        // ###########################################################
        function filtrarAlumnos() {
            const filtroTexto = inputNombreAlumno.value.toLowerCase();
            const filtroCarrera = inputFiltroCarreraAlumno.value.toLowerCase();
            
            // Índices de la tabla Alumnos (DNI=0, Nombre=1, Carreras=6)
            filtrarTabla(tablaAlumnos, filtroTexto, filtroCarrera, 0, 1, 6);
        }


        // ###########################################################
        // 5. Asignación de Eventos
        // ###########################################################

        // Eventos para Profesores (Text input y Combo de carrera)
        inputFiltroProfesor.addEventListener('keyup', filtrarProfesores);
        inputFiltroCarreraProfesor.addEventListener('change', filtrarProfesores);

        // Eventos para Alumnos (Text input y Combo de carrera)
        inputNombreAlumno.addEventListener('keyup', filtrarAlumnos);
        inputFiltroCarreraAlumno.addEventListener('change', filtrarAlumnos);
    });
</script>

</body>
</html>