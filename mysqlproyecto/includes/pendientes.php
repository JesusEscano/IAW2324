<!-- Header -->
<?php session_start()?>
<?php if($_SESSION['user']){
}else{
 header("Location: ../login.php");
}?>
<?php include "../header.php"?>
<?php include "login.php"?>

  <div class="container">

  <?php
        $totalc = "SELECT COUNT(*) as total FROM incidencias";
        $resueltasc = "SELECT COUNT(*) as resueltas FROM incidencias WHERE fecha_sol IS NOT NULL AND fecha_sol <> '0000-00-00'";
        $pendientesc = "SELECT COUNT(*) as pendientes FROM incidencias WHERE fecha_sol IS NULL";

        $rctotal = mysqli_query($conn, $totalc);
        $rcresueltas = mysqli_query($conn, $resueltasc);
        $rcpendientes = mysqli_query($conn, $pendientesc);

        $total = mysqli_fetch_assoc($rctotal)['total'];
        $resueltas = mysqli_fetch_assoc($rcresueltas)['resueltas'];
        $pendientes = mysqli_fetch_assoc($rcpendientes)['pendientes'];
    ?>

    <h1 class="text-center" >Gestión de incidencias (CRUD)</h1>
    <h3 class="text-center" >Bienvenido, <?php print_r($_SESSION["user"]);?></h3>
      <div class="mb-3">
    <table class="table">
        <tr class="table-dark">
            <th>Total de incidencias</th>
            <th>Incidencias resueltas</th>
            <th>Incidencias pendientes</th>
        </tr>
        <tr>
            <td class="celdasinfo celdatotal"><?php echo $total; ?></td>
            <td class="celdasinfo celdartas"><?php echo $resueltas; ?></td>
            <td class="celdasinfo celdaptes"><?php echo $pendientes; ?></td>
        </tr>
    </table>
</div>
        <table class="table table-striped table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th  scope="col">ID</th>
              <th  scope="col">Planta</th>
              <th  scope="col">Aula</th>
              <th  scope="col">Descripción</th>
              <th  scope="col">Fecha alta</th>
              <th  scope="col">Fecha revisión</th>
              <th  scope="col">Fecha solución</th>
              <th  scope="col">Comentario</th>
              <th  scope="col" colspan="3" class="text-center">Operaciones</th>
            </tr>  
          </thead>
            <tbody>
              <tr>
 
          <?php
            $query="SELECT * FROM incidencias WHERE fecha_sol IS NULL";               
            $vista_incidencias= mysqli_query($conn,$query);

            while($row= mysqli_fetch_assoc($vista_incidencias)){
              $id = $row['id'];                
              $planta = $row['planta'];        
              $aula = $row['aula'];         
              $descripcion = $row['descripcion'];        
              $fecha_alta = $row['fecha_alta'];        
              $fecha_rev = $row['fecha_rev'];        
              $fecha_sol = $row['fecha_sol'];        
              $comentario = $row['comentario']; 
              echo "<tr >";
              echo " <th scope='row' >{$id}</th>";
              echo " <td > {$planta}</td>";
              echo " <td > {$aula}</td>";
              echo " <td >{$descripcion} </td>";
              echo " <td >{$fecha_alta} </td>";
              echo " <td >{$fecha_rev} </td>";
              echo " <td >{$fecha_sol} </td>";
              echo " <td >{$comentario} </td>";
              echo " <td class='text-center'> <a href='view.php?incidencia_id={$id}' class='btn btn-primary'> <i class='bi bi-eye'></i> Ver</a> </td>";
              echo " <td class='text-center' > <a href='update.php?editar&incidencia_id={$id}' class='btn btn-secondary'><i class='bi bi-pencil'></i> Editar</a> </td>";
              echo " <td class='text-center'>  <a href='delete.php?eliminar={$id}' class='btn btn-danger'> <i class='bi bi-trash'></i> Eliminar</a> </td>";
              echo " </tr> ";
                  }  
                ?>
              </tr>  
            </tbody>
        </table>
  </div>
<div class="container text-center mt-5">
      <a href="../login.php" class="btn btn-warning mt-5"> Volver </a>
    <div>
<?php include "../footer.php" ?>