<?php
// public/index.php

// Iniciar sesión (necesaria para el login, perfiles y mensajes)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Definir constante ROOT_PATH
define('ROOT_PATH', dirname(__DIR__) . '/');

// Incluir el autocargador
require_once ROOT_PATH . 'vendor/autoload.php';

// Iniciar el Router
$router = new Router();
$router->run();