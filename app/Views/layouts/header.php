<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="icon" type="image/x-icon" href="assets/imagen/instituto.ico">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?= isset($pageTitle) ? $pageTitle : 'Instituto 93' ?></title>
</head>
<body>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="sessionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto toast-title">Notificación</strong>
                <small class="text-white">Ahora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                </div>
        </div>
    </div>

    <header class="mb-3">
        <nav class="d-flex align-items-center gap-4">
            <img src="/assets/imagen/instituto.png" alt="Instituto 93" class="m-3" style="height: 5rem; vertical-align: middle;">
            <a href="/" class="none" style="color: white;">Instituto Superior de Formación Docente y Técnica Nº 93 Arturo Umberto Illia</a>
        </nav>
    </header>

    <main class="container">