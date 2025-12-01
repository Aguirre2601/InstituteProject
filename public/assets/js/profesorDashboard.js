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

