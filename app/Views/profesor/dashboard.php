<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Profesor</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .warning { color: orange; }
        /* Estilos para la barra de filtros */
        .filtros-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .filtros-container select, .filtros-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .filtros-container input {
            flex-grow: 1; /* El buscador de nombre ocupa el espacio restante */
        }
    </style>
</head>
<body>
        <h1>Bienvenido Profesor/a, <?= $profesor->nombre . ' ' . $profesor->apellido ?></h1>
    <p>Rol: Profesor | Usuario: <?= $profesor->usuario_name ?></p>
    
    <p>
        <a href="/profesor/vistaEditarPerfil">✏️ Editar Mi Perfil</a> | 
        <a href="/auth/logout">🚪 Cerrar Sesión</a>
    </p>

    <hr>
    
    <?php 
    if (isset($_SESSION['mensaje'])): 
    ?>
        <p class="success"><?= $_SESSION['mensaje'] ?></p>
    <?php 
        unset($_SESSION['mensaje']); 
    endif; 
    ?>
    <h2>🧑‍🎓 Alumnos Asignados (<?= count($alumnos) ?>)</h2>
    
    <div class="filtros-container">
        <strong>🔍 Filtrar:</strong>
        
        <select id="filtroCarrera">
            <option value="">Todas las Carreras</option>
            <?php foreach ($carreras_filtro as $carrera): ?>
                <option value="<?= $carrera->descripcion ?>"><?= $carrera->descripcion ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" id="filtroNombre" placeholder="Buscar por nombre o apellido...">
    </div>

    <?php if (empty($alumnos)): ?>
        <p class="warning">No hay alumnos asignados.</p>
    <?php else: ?>
    
    <table id="tablaAlumnos">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre y Apellido</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Localidad</th>
                <th>Carrera</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
            <tr> <td><?= $alumno->dni ?></td>
                <td><?= $alumno->nombre . ' ' . $alumno->apellido ?></td>
                
                <td><?= $alumno->telefono ?></td>
                <td><?= $alumno->email ?></td>
                <td><?= $alumno->localidad_nombre ?></td>
                <td style="font-weight:bold; color:#555;"><?= $alumno->carrera_nombre ?></td>
                <td>
                    
                    <a href="/profesor/darDeBajaAlumno/<?= $alumno->id ?>/<?= $alumno->id_carrera ?>"
                        onclick="return confirm('¿Está seguro de dar de baja al Alumno <?= $alumno->apellido ?> de la carrera <?= $alumno->carrera_nombre ?>?');">
                        Dar de Baja de Carrera
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p id="sinResultados" style="display:none; color:red; text-align:center; margin-top:10px;">
        No se encontraron alumnos con esos filtros.
    </p>

    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputCarrera = document.getElementById('filtroCarrera');
            const inputNombre = document.getElementById('filtroNombre');
            const tabla = document.getElementById('tablaAlumnos');
            const mensajeSinResultados = document.getElementById('sinResultados');
            
            // Si no hay tabla (no hay alumnos), no ejecutamos el script
            if (!tabla) return;

            const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            function filtrarTabla() {
                const filtroCarrera = inputCarrera.value.toLowerCase();
                const filtroNombre = inputNombre.value.toLowerCase();
                let hayResultados = false;

                for (let i = 0; i < filas.length; i++) {
                    const celdaCarrera = filas[i].getElementsByTagName('td')[5]; // Columna 5 (Carrera)
                    const celdaNombre = filas[i].getElementsByTagName('td')[1];  // Columna 1 (Nombre)

                    if (celdaCarrera && celdaNombre) {
                        const textoCarrera = celdaCarrera.textContent || celdaCarrera.innerText;
                        const textoNombre = celdaNombre.textContent || celdaNombre.innerText;

                        // Lógica de comparación
                        // 1. Si el combo de carrera está vacío, coincide siempre. Si no, debe ser idéntico o contener el texto.
                        const coincideCarrera = filtroCarrera === "" || textoCarrera.toLowerCase().includes(filtroCarrera);
                        
                        // 2. El nombre debe contener lo que se escribe
                        const coincideNombre = textoNombre.toLowerCase().includes(filtroNombre);

                        // Mostrar u ocultar la fila
                        if (coincideCarrera && coincideNombre) {
                            filas[i].style.display = "";
                            hayResultados = true;
                        } else {
                            filas[i].style.display = "none";
                        }
                    }
                }

                // Mostrar mensaje si se ocultaron todas las filas
                mensajeSinResultados.style.display = hayResultados ? 'none' : 'block';
            }

            // Escuchar eventos: 'change' para el select, 'keyup' para escribir en el input
            inputCarrera.addEventListener('change', filtrarTabla);
            inputNombre.addEventListener('keyup', filtrarTabla);
        });
    </script>

</body>
</html>