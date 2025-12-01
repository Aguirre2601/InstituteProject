document.addEventListener('DOMContentLoaded', function() {
        // PROFESORES:
        const inputFiltroCarreraProfesor = document.getElementById('filtroCarreraProfesor'); 
        const inputFiltroProfesor = document.getElementById('filtroProfesor');           
        const tablaProfesores = document.getElementById('tablaProfesores');
        
        // ALUMNOS:
        const inputFiltroCarreraAlumno = document.getElementById('filtroCarrera');       
        const inputNombreAlumno = document.getElementById('filtroNombreAlumno');        
        const tablaAlumnos = document.getElementById('tablaAlumnos');

        // ELEMENTOS DE MENSAJE DE NO RESULTADOS
        const mensajeSinResultadosA = document.getElementById('sinResultadosA');
        const mensajeSinResultadosP = document.getElementById('sinResultadosP');
        
        /**
         * Aplica filtros de texto/DNI y carrera a una tabla específica.
         * @param {HTMLElement} tablaElement - La tabla a filtrar.
         * @param {string} filtroTexto - Texto de búsqueda (nombre/dni).
         * @param {string} filtroCarrera - Valor seleccionado del combobox de carrera.
         * @param {number} idxDNI - Índice de la columna DNI.
         * @param {number} idxNombre - Índice de la columna Nombre/Apellido.
         * @param {number} idxCarrera - Índice de la columna Carreras.
         * @param {HTMLElement} mensajeElement - El elemento P que muestra el mensaje de no resultados.
         */
        function filtrarTabla(tablaElement, filtroTexto, filtroCarrera, idxDNI, idxNombre, idxCarrera, mensajeElement) {
            if (!tablaElement) return;

            const filas = tablaElement.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            let filasVisibles = 0; // Contador de filas que SÍ coinciden con el filtro
            
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
                
                // Si la fila se muestra, incrementamos el contador
                if (mostrar) {
                    filasVisibles++;
                }
            }
            
            // --- MOSTRAR MENSAJE ---
            // Si el contador de filas visibles es CERO, mostramos el mensaje.
            if (mensajeElement) {
                mensajeElement.style.display = (filasVisibles === 0) ? 'block' : 'none';
            }
        }

        function filtrarProfesores() {
            const filtroTexto = inputFiltroProfesor.value.toLowerCase();
            const filtroCarrera = inputFiltroCarreraProfesor.value.toLowerCase();
            
            // Índices de la tabla Profesores (DNI=0, Nombre=1, Carreras=7). Se añade el elemento del mensaje.
            filtrarTabla(tablaProfesores, filtroTexto, filtroCarrera, 0, 1, 7, mensajeSinResultadosP);
        }
        
        function filtrarAlumnos() {
            const filtroTexto = inputNombreAlumno.value.toLowerCase();
            const filtroCarrera = inputFiltroCarreraAlumno.value.toLowerCase();
            
            // Índices de la tabla Alumnos (DNI=0, Nombre=1, Carreras=6). Se añade el elemento del mensaje.
            filtrarTabla(tablaAlumnos, filtroTexto, filtroCarrera, 0, 1, 6, mensajeSinResultadosA);
        }


        // Eventos para Profesores (Text input y Combo de carrera)
        inputFiltroProfesor.addEventListener('keyup', filtrarProfesores);
        inputFiltroCarreraProfesor.addEventListener('change', filtrarProfesores);

        // Eventos para Alumnos (Text input y Combo de carrera)
        inputNombreAlumno.addEventListener('keyup', filtrarAlumnos);
        inputFiltroCarreraAlumno.addEventListener('change', filtrarAlumnos);
});