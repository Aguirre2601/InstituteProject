<?php

require_once __DIR__ . '/composer/autoload_real.php';

// vendor/autoload.php
 //Requiere que la constante ROOT_PATH esté definida previamente.
function autoloader($className) {
    // Buscar en core/
    $file = ROOT_PATH . 'core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Buscar en Middleware/
    $file = ROOT_PATH . 'app/Middleware/' . $className . '.php';
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

