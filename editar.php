<!--Se realiza la conección a la bd.
Después se obtiene el id con get.
Se seleccionan todos los datos con ese id.
Se busca un resultado con ese id.
En $row se cargan los datos en un array y se vuelcan en variables con el título de las columnas

Se carga el header y footer con include
-->|

<?php

include("conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "select * from alumnos where id=$id";
    $resultado = mysqli_query($conn, $query);

    if (mysqli_num_rows($resultado)==1){
        $row = mysqli_fetch_array($resultado);
        $dni = $row['dni'];
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $telefono = $row['telefono'];
        $mail = $row['mail'];
        $carrera = $row['carrera'];
        $localidad = $row['localidad'];
        $calle = $row['calle'];


    }

    }

    
    if (isset($_POST['actualizar'])){
     $id = $_GET['id'];
     $dni = $_POST['dni'];
     $nombre = $_POST['nombre'];
     $apellido = $_POST['apellido'];
     $telefono = $row['telefono'];
     $mail = $_POST['mail'];
     $carrera = $_POST['carrera'];
     $localidad = $_POST['localidad'];
     $calle = $_POST['calle'];

     $query = "update alumnos set dni='$dni', nombre='$nombre', apellido='$apellido', telefono='$telefono', mail='$mail', carrera='$carrera', localidad='$localidad', calle='$calle' where id=$id";
     
     mysqli_query($conn, $query);

     $_SESSION ['message'] ="El registro se actualizó correctamente";

     header("location: index.php");

    }
    
    ?>

    <?php include("includes/header.php"); ?>

   <div class="container p-4">

   <div class="row">

    <col-md4 mx-auto>
        
        <div class="card card-body " style="background-color: #1e73be;">
            <div class="titulo">
                    <img src="imagen/editar.png">
                    <h3>Editar Alumno</h3>
                </div>
            <!--Actualizar con método POST-->
    <form action="editar.php? id=<?php echo $_GET['id']; ?>" method="POST">
        <form-group>
        <input type="text" name="dni" value="<?php echo $dni; ?>" class="form-control" placeholder="Actualizar DNI"><br>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" class="form-control" placeholder="Actualizar nombre"><br>
            <input type="text" name="apellido" value="<?php echo $apellido; ?>" class="form-control" placeholder="Actualizar apellido"><br>
            <input type="text" name="telefono" value="<?php echo $telefono; ?>" class="form-control" placeholder="Actualizar teléfono"><br>
            <input type="text" name="mail" value="<?php echo $mail; ?>" class="form-control" placeholder="Actualizar Mail"><br>
            <input type="text" name="carrera" value="<?php echo $carrera; ?>" class="form-control" placeholder="Actualizar Carrera"><br>
            <input type="text" name="localidad" value="<?php echo $localidad; ?>" class="form-control" placeholder="Actualizar Localidad"><br>
            <input type="text" name="calle" value="<?php echo $calle; ?>" class="form-control" placeholder="Actualizar Calle"><br>
        
        <br>
        <div class="botones">
        <a href="index.php" type="button" class="btn btn-success botonVolver" style="background-color: #ae0d18; ">Volver</a>
        <button class="btn btn-success" name="actualizar" style="background-color: green;">Actualizar</button>
        </div>
    </form>
        </div>    

        </col-md>

   </div>

   </div>
    

    <?php include("includes/footer.php"); ?>
