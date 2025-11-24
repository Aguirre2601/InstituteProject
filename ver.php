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

    }

    }

    
    
    ?>

    <?php include("includes/header.php"); ?>

   <div class="container p-4">

   <div class="row">

    <col-md4 mx-auto>
        
        <div class="card card-body ">
            <!--Actualizar con método POST-->
    <form action="">
        <form-group>
           
        <input type="text" name="dni" value="<?php echo $dni; ?>" class="form-control" placeholder="Actualizar DNI"><br>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" class="form-control" placeholder="Actualizar nombre"><br>
            <input type="text" name="apellido" value="<?php echo $apellido; ?>" class="form-control" placeholder="Actualizar apellido"><br>
            <input type="text" name="telefono" value="<?php echo $telefono; ?>" class="form-control" placeholder="Actualizar teléfono"><br>
           

        </form-group>
        <br>
   <a href="index.php" type="button" class="btn btn-success" style="background-color: green;">Volver</a>
    </form>
        </div>    

        </col-md>

   </div>

   </div>
    

    <?php include("includes/footer.php"); ?>
