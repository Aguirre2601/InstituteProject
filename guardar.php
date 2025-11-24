<?php

include("conexion.php");

include("includes/header.php");

if(isset($_POST ['guardar-alumno'])){
    
    if(!empty($_POST['dni'])) {

        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $mail = $_POST['mail'];
        $carrera = $_POST['carrera'];
        $localidad = $_POST['localidad'];
        $calle = $_POST['calle'];
    
        $query = "insert into alumnos (dni, nombre, apellido, telefono, mail, carrera, localidad, calle) values ('$dni', '$nombre', '$apellido', '$telefono', '$mail', '$carrera', '$localidad', '$calle')";
    
        $resultado = mysqli_query($conn, $query);
    
        if (!$resultado){
            die("Conexión fallida");
        }
    
        $_SESSION['message'] = 'Registro guardado con éxito';
    
        header ("Location: index.php");
    } else {
        $_SESSION['message'] = 'Completar todos los datos';
    
        header ("Location: index.php");
    }
    
}

?>


