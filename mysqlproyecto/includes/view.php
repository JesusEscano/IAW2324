<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['perfil'] !== 'administrador' && $_SESSION['perfil'] !== 'direccion') || !isset($_SESSION['token'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php";
include "login.php";
?>

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


    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <a class="navbar-brand"><img src="../IESlogo.jpg" width="30" height="30" class="d-inline-block align-top" alt="Logo Machado"> VIENDO INCIDENCIA</a>
                    <li class="nav-item" style="margin-right: 1px;">
                        <a class="nav-link btn btn-warning" href="create.php" style="font-size: 18px; color: black;"><i class="bi bi-plus-circle"></i> Añadir incidencia</a>
                    </li>
                    <li class="nav-item" style="margin-right: 1px;">
                        <a class="nav-link btn btn-success" href="resueltas.php" style="font-size: 18px; color: #fff;"><i class="bi bi-check-circle"></i> Incidencias resueltas (<?php echo $resueltas; ?>)</a>
                    </li>
                    <li class="nav-item" style="margin-right: 1px;">
                        <a class="nav-link btn btn-danger" href="pendientes.php" style="font-size: 18px; color: #fff;"><i class="bi bi-exclamation-circle"></i> Incidencias pendientes (<?php echo $pendientes; ?>)</a>
                    </li>
                    <li class="nav-item" style="margin-right: 1px;">
                        <a class="nav-link btn btn-primary" href="home.php" style="font-size: 18px; color: #fff;"><i class="bi bi-house-door"></i> Ver todas (<?php echo $total; ?>)</a>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="dropbtn">
                                <a class="nav-link btn btn btn-light" style="font-size: 18px; color: black;">
                                    <i class="bi bi-person"></i> <?php print_r($_SESSION["user"]);?>
                                </a>
                            </button>
                            <div class="dropdown-content">
                                <a href="../logout.php"><i class="bi bi-box-arrow-left"></i> Desconectar</a>
                                <?php
                                if ($_SESSION['perfil'] === 'administrador') {
                                    echo '<a href="admin.php"><i class="bi bi-database-fill-gear"></i> Administrar</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Planta</th>
                    <th scope="col">Aula</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Usuario</th>
                    <th scope="col" style="width: 120px;">Fecha alta</th>
                    <th scope="col" style="width: 120px;">Fecha revisión</th>
                    <th scope="col" style="width: 120px;">Fecha solución</th>
                    <th scope="col">Comentario</th>
                </tr>  
            </thead>
            <tbody>
                <tr>
                    <?php
                    if (isset($_GET['incidencia_id'])) {
                        $incidenciaid = htmlspecialchars($_GET['incidencia_id']); 
                        $query="SELECT incidencias.*, plantas.planta, aulas.aula, usuarios.usuario FROM incidencias INNER JOIN plantas ON incidencias.planta_id = plantas.id INNER JOIN aulas ON incidencias.aula_id = aulas.id INNER JOIN usuarios ON incidencias.usuario_id = usuarios.id WHERE incidencias.id = $incidenciaid LIMIT 1";  
                        $vista_incidencias= mysqli_query($conn,$query);            

                        while($row = mysqli_fetch_assoc($vista_incidencias))
                        {
                            $id = $row['id'];                
                            $planta_nombre = $row['planta'];
                            $aula_nombre = $row['aula'];  
                            $descripcion = $row['descripcion'];        
                            $usuario_nombre = $row['usuario'];         
                            $fecha_alta = formatoFecha($row['fecha_alta']);        
                            $fecha_rev = formatoFecha($row['fecha_rev']);        
                            $fecha_sol = formatoFecha($row['fecha_sol']);          
                            $comentario = $row['comentario'];

                            echo "<tr >";
                            echo " <th scope='row' >{$id}</th>";
                            echo " <td > {$planta_nombre}</td>";
                            echo " <td > {$aula_nombre}</td>";
                            echo " <td >{$descripcion} </td>";
                            echo " <td >{$usuario_nombre} </td>";
                            echo " <td >{$fecha_alta} </td>";
                            echo " <td >{$fecha_rev} </td>";
                            echo " <td >{$fecha_sol} </td>";
                            echo " <td >{$comentario} </td>";
                            echo " </tr> ";
                        }
                    }
                    ?>
                </tr>  
            </tbody>
        </table>
    </div>
</div>
<?php
function formatoFecha($fecha)
{
    // Convierte la fecha de formato americano (yyyy-mm-dd) a formato europeo (dd-mm-yyyy)
    if ($fecha != '0000-00-00') {
        return date('d-m-Y', strtotime($fecha));
    } else {
        return '';
    }
}
?>
<div class="text-center mt-3"><?php include "../footer.php" ?></div>