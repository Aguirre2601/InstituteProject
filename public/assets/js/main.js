document.addEventListener('DOMContentLoaded', function() {
    const toastLiveExample = document.getElementById('sessionToast'); 

    // 3. Verifica si hay datos de Toast que PHP inyectó
    if (window.sessionToastData && toastLiveExample) {
        
        const data = window.sessionToastData;
        const toastBody = toastLiveExample.querySelector('.toast-body');
        const toastHeader = toastLiveExample.querySelector('.toast-header');
        
        // 4. Llenar contenido
        toastBody.innerHTML = data.message;
        
        // 5. Aplicar la clase de color al header y al toast principal
        toastHeader.classList.add(data.class);
        toastLiveExample.classList.add(data.class);
        
        // 6. Inicializar y Mostrar el Toast
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
        
        // Limpiar la variable global después de mostrarlo
        delete window.sessionToastData;
    }
});