<!--Se hace la conexión a la bd tareas, se recupera el id con $_GET y se realiza la consulta-->

<?php

include("conexion.php");

if(isset($_GET['id'])) {

    $id = $_GET['id'];

    $query ="delete from alumnos where id=$id";
    $resultado = mysqli_query($conn, $query);

    if(!$resultado) {
       die("Conexión fallida"); 
    }

    $_SESSION['message'] = 'Registro eliminado correctamente';

    header ("location: index.php");
}

?>