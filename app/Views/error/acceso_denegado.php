<!-- app/Views/error/acceso_denegado.php-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        /* Estilo personalizado para centrar el fantasma y el contenido */
        .error-container {
            min-height: 100vh; /* Ocupa toda la altura de la vista */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .ghost-icon {
            font-size: 8rem; /* Tamaño grande para el emoji */
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="bg-danger-subtle"> 
    
    <div class="error-container">
        
        <div class="ghost-icon text-danger">
            👻
        </div>

        <div class="card p-5 shadow-lg text-center" style="max-width: 500px;">
            
            <h1 class="card-title display-4 text-danger mb-3">
                ⚠️ ERROR 404
            </h1>
            
            <h2 class="card-subtitle mb-4 text-dark">
                ¡Aquí no puedes entrar!
            </h2>
            
            <p class="lead text-secondary">
                No tienes permiso para acceder a esta página o el recurso solicitado no existe.
            </p>
            
            <hr class="my-4">

            <a href="/" class="btn btn-primary btn-lg mt-3">
                Volver a Inicio/Login
            </a>
            
        </div>
        
    </div>

</body>
</html>