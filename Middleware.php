<?php
// Inicia la sesión de PHP
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: login.php");
    exit(); 
}

function verificar_rol(array $rol_permitido) {
    if (!isset($_SESSION['rol'])) {
        header("Location: login.php");
        exit();
    }

    $rol_actual = $_SESSION['rol']; //si el user de la sesion tare un rol, lo guardo en la var
    
    if (!in_array($rol_actual, $rol_permitido)) {
        header("Location: acceso_denegado.php"); 
        exit();
    }
}
?>