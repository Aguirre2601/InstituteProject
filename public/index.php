<?php
//Iniciar la sesión (necesaria para el login, perfiles y mensajes)
session_start();

//Definir la zona horaria (configuración global)
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Incluir el autocargador de clases (Autoloader)
// Esto evita que tengas que hacer "require_once" para cada clase (Database, Usuario, Router, etc.)

// Definimos la constante ROOT para que todas las rutas sean relativas a la raíz del proyecto
define('ROOT_PATH', dirname(__DIR__) . '/');

// Función Autoloader: Carga clases automáticamente desde 'core/' y 'app/'
function autoloader($className) {
    // Buscar en core/
    $file = ROOT_PATH . 'core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Buscar en models/
    $file = ROOT_PATH . 'app/Models/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Buscar en controllers/
    $file = ROOT_PATH . 'app/Controllers/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
}

// Registrar la función de autocarga
spl_autoload_register('autoloader');


// Iniciar el Router
$router = new Router();
$router->run();