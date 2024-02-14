<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['perfil'] !== 'administrador' && $_SESSION['perfil'] !== 'direccion') || !isset($_SESSION['token'])) {
    header("Location: ../login.php");
    exit();
}
include "../header.php";
include "login.php";

if(isset($_GET['incidencia_id'])) {
    $incidenciaid = htmlspecialchars($_GET['incidencia_id']); 
}

$query_plantas = "SELECT * FROM plantas";
$result_plantas = mysqli_query($conn, $query_plantas);
$opciones_planta = array();
while ($row = mysqli_fetch_assoc($result_plantas)) {
    $opciones_planta[$row['id']] = $row['planta'];
}

$query_aulas = "SELECT * FROM aulas";
$result_aulas = mysqli_query($conn, $query_aulas);
$opciones_aula = array();
while ($row = mysqli_fetch_assoc($result_aulas)) {
    $opciones_aula[$row['id']] = $row['aula'];
}

$query = "SELECT * FROM incidencias WHERE id = $incidenciaid";
$vista_incidencias = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($vista_incidencias)) {
    $id = $row['id'];                
    $planta_id = $row['planta_id']; 
    $aula_id = $row['aula_id'];  
    $descripcion = $row['descripcion'];        
    $fecha_alta = $row['fecha_alta'];        
    $fecha_rev = $row['fecha_rev'];        
    $fecha_sol = $row['fecha_sol'];        
    $comentario = $row['comentario'];
}
 
if(isset($_POST['editar'])) {
    $planta_id = htmlspecialchars($_POST['planta']);
    $aula_id = htmlspecialchars($_POST['aula']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $fecha_alta = htmlspecialchars($_POST['fecha_alta']);
    $fecha_rev = htmlspecialchars($_POST['fecha_rev']);
    $fecha_sol = htmlspecialchars($_POST['fecha_sol']);
    $comentario = htmlspecialchars($_POST['comentario']);
    $query = "UPDATE incidencias SET planta_id = '{$planta_id}' , aula_id = '{$aula_id}' , descripcion = '{$descripcion}', fecha_alta = '{$fecha_alta}', fecha_rev = '{$fecha_rev}', fecha_sol = '{$fecha_sol}', comentario = '{$comentario}' WHERE id = {$id}";
    $incidencia_actualizada = mysqli_query($conn, $query);
    if (!$incidencia_actualizada) {
        echo "Se ha producido un error al actualizar la incidencia.";
    } else {
        echo "<script type='text/javascript'>alert('¡Datos de la incidencia actualizados!'); window.location.href = 'home.php';</script>";
        exit;
    }
}             
?>

<h1 class="text-center">Actualizar incidencia</h1>
<div class="container">
    <form action="" method="post">
        <div class="form-group">
            <label for="planta">Planta</label>
            <select name="planta" class="form-control">
                <?php foreach ($opciones_planta as $planta_id_opcion => $nombre_planta): ?>
                    <option value="<?php echo $planta_id_opcion; ?>" <?php if ($planta_id_opcion == $planta_id) echo 'selected'; ?>><?php echo $nombre_planta; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="aula">Aula</label>
            <select name="aula" class="form-control">
                <?php foreach ($opciones_aula as $aula_id_opcion => $nombre_aula): ?>
                    <option value="<?php echo $aula_id_opcion; ?>" <?php if ($aula_id_opcion == $aula_id) echo 'selected'; ?>><?php echo $nombre_aula; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $descripcion; ?>">
        </div>
        <div class="form-group">
            <label for="fecha_alta">Fecha alta</label>
            <input type="date" name="fecha_alta" class="form-control" value="<?php echo $fecha_alta; ?>">
        </div>
        <div class="form-group">
            <label for="fecha_rev">Fecha revisión</label>
            <input type="date" name="fecha_rev" class="form-control" value="<?php echo $fecha_rev; ?>" min="<?php echo date('Y-m-d'); ?>" id="noTangar">
        </div>
        <div class="form-group">
            <label for="fecha_sol">Fecha solución</label>
            <input type="date" name="fecha_sol" class="form-control" value="<?php echo $fecha_sol; ?>" min="<?php echo date('Y-m-d'); ?>" id="fechaLimitada">
        </div>
        <div class="form-group">
            <label for="comentario">Comentario</label>
            <input type="text" name="comentario" class="form-control" value="<?php echo $comentario; ?>">
        </div>
        <div class="form-group">
            <input type="submit"  name="editar" class="btn btn-primary mt-2" value="Editar">
        </div>
    </form>    
</div>
<script>
// Evitar poner fecha de revisión posterior a fecha de solución
var LoDeRevision = document.getElementById('noTangar');
var LoDeSolucion = document.getElementById('fechaLimitada');

// Evento al cambiar la fecha de revisión
LoDeRevision.addEventListener('change', function() {
    var fechaRev = LoDeRevision.value;
    LoDeSolucion.min = fechaRev;
});

// Evento al cambiar la fecha de solución
LoDeSolucion.addEventListener('change', function() {
    var fechaSol = LoDeSolucion.value;
    var fechaRev = LoDeRevision.value;

    // Verificar y ajustar la fecha de revisión si es necesario
    if (fechaRev !== '' && fechaSol < fechaRev) {
      LoDeRevision.value = fechaSol;
      LoDeRevision.min = fechaSol;
    }
});
</script>
<div class="container text-center mt-5">
  <a href="home.php" class="btn btn-warning mt-5"> Volver </a>
</div>

<?php include "../footer.php"; ?>