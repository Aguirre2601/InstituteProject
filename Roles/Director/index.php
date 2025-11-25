<?php include("conexion.php");?>
<?php include_once("Middleware.php"); 
verificar_rol(['D']); ?>
<?php include("includes/header.php");?>


<div class="container p-4" >
    <div class="row">
        <div class="col-md-4">
            <div class="card card-body" style="background-color: #1e73be ;" >
                <div class="titulo">
                    <img src="imagen/buscar.png">
                    <h3>Buscar alumno</h3>
                </div>
                <form action="index.php" method="post">

                    <input type="text" name="apellido" class="form-control" placeholder="Apellido"><br>

                    <input type="submit" class="btn btn-secondary btn-block float-start botonTodosAlumnos" name="todos-alumnos" value="Todos los alumnos">

                    <input type="submit" class="btn btn-success btn-block float-end botonBuscar" name="buscar-alumno" value="Buscar" style="background-color: green;">
                </form>
            </div>
            <br>
        </div>

        <div class="col-md-8" >
        <!--Mensaje de tarea-->
        <?php
        if(isset($_SESSION['message'])) { ?>
    
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.href='index.php'"></button>
            </div>
            <?php session_unset(); } ?>
        <!--Guardar tarea-->
               <div class="card card-body" style="background-color: #1e73be ;">
                <div class="titulo">
                    <img src="imagen/chico.png">
                    <h3>Nuevo Alumno</h3>
                </div>
            <form action="guardar.php" method="post">
                <div class="form-group">
                    <input type="text" name="dni" class="form-control" placeholder="Ingresar DNI" autofocus><br>
                    <input type="text" name="nombre" class="form-control" placeholder="Ingresar Nombre" autofocus><br>
                    <input type="text" name="apellido" class="form-control" placeholder="Ingresar Apellido" autofocus><br>
                    <input type="text" name="telefono" class="form-control" placeholder="Ingresar Teléfono" autofocus><br>
                    <input type="text" name="mail" class="form-control" placeholder="Ingresar Mail" autofocus><br>
                    <input type="text" name="carrera" class="form-control" placeholder="Ingresar Carrera" autofocus><br>
                    <input type="text" name="localidad" class="form-control" placeholder="Ingresar Localidad" autofocus><br>
                    <input type="text" name="calle" class="form-control" placeholder="Ingresar Calle" autofocus><br>
                </div>
                <br>
                <div class="botones">
                <input type="submit" class="btn btn-success btn-block float-end " name="guardar-alumno" value="Guardar alumno" style="background-color: green ;">
                </div>
            </form>

            </div>
            </div>


	<script type="text/javascript">
	    function confirmar(){
	    return confirm('¿Quiere borrar registro de alumno?.');
	} </script> 
            

        <!--Tabla-->
        <div class="card card-body tabla" style="background-color: #1e73be ;">
            <div class="titulo">
                    <img src="imagen/documento.png">
                    <h3>Listado de alumnos</h3>
                </div>

        <table class="table table-responsive table-bordered">
            <br>
            <br>


            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Mail</th>
                    <th>Carrera</th>
                    <th>Localidad</th>
                    <th>Calle</th>
                </tr>
            </thead>
        </div>
              <!--Consulta con el select de todos los datos en tbody-->
            <tbody>

            <?php
                    if (isset($_POST['buscar-alumno'])){
                        
                        $apellido = $_POST['apellido'];

    
                        $query = "select * from alumnos where apellido like '%$apellido' ";
                        $resultado = mysqli_query($conn, $query);
    
                        while($row = mysqli_fetch_array($resultado)) { ?>
                        <tr>
                            <td> <?php echo $row['dni']; ?></td>
                            <td> <?php echo $row['nombre']; ?></td>
                            <td> <?php echo $row['apellido']; ?></td>
                            <td> <?php echo $row['telefono']; ?></td>
                            <td> <?php echo $row['mail']; ?></td>
                            <td> <?php echo $row['carrera']; ?></td>
                            <td> <?php echo $row['localidad']; ?></td>
                            <td> <?php echo $row['calle']; ?></td>
                            <td>

                            <a href="ver.php?id=<?php echo $row['id']?>" class="btn btn-success"><img src="includes/verojo.png"></a>    
                            <a href="editar.php?id=<?php echo $row['id']?>" class="btn btn-secondary"><img src="includes/editar.png"></a>
                            <a href="eliminar.php?id=<?php echo $row['id']?>" class="btn btn-danger"><img src="includes/eliminar.png"></a>
                      
                            </td>
                        </tr>

                    
                        <?php }

                    } else  { ?>
            
                <?php 

                $query = "select * from alumnos";
                $resultado = mysqli_query ($conn, $query);

                while($row = mysqli_fetch_array($resultado)) { ?>
                    <tr>
                        <td> <?php echo $row['dni']; ?></td>
                        <td> <?php echo $row['nombre']; ?></td>
                        <td> <?php echo $row['apellido']; ?></td>
                        <td> <?php echo $row['telefono']; ?></td>
                        <td> <?php echo $row['mail']; ?></td>
                        <td> <?php echo $row['carrera']; ?></td>
                        <td> <?php echo $row['localidad']; ?></td>
                        <td> <?php echo $row['calle']; ?></td>
                        <td>

        
                        <a href="ver.php?id=<?php echo $row['id']?>" class="btn btn-success"> Ver</a>
                            <a href="editar.php?id=<?php echo $row['id']?>" class="btn btn-secondary">Editar</a>
                            <?php echo "<a href='eliminar.php?id=".$row['id']."' onclick='return confirmar()' class='btn btn-danger'>Eliminar</a>"; ?>

                            
                        </td>
                    </tr>

            <?php } } ?>

            </tbody>
        </table>
        
        
        </div>

    </div>
    
</div>



<?php ("includes/footer.php") ?>