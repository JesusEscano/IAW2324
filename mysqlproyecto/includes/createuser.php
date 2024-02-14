<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['token'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php";
include "login.php";

$usuario_activo = $_SESSION['user'];
$query_usuario = "SELECT id FROM usuarios WHERE usuario = '$usuario_activo'";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $usuario_id = $fila_usuario['id'];
} else {
    die("Error al obtener el ID del usuario: " . mysqli_error($conn));
}

// Array para las plantas y aulas
$aulayplanta = array(
    'Baja' => array("1", "2", "3"),
    'Primera' => array("101", "102", "103"),
    'Segunda' => array("201", "202", "203")
);

// Función para mostrar las aulas según la planta seleccionada
function mostrar_aulas($planta) {
    global $aulayplanta;
    $aulas = isset($aulayplanta[$planta]) ? $aulayplanta[$planta] : array();
    foreach ($aulas as $aula) {
        echo '<option value="' . $aula . '">' . $aula . '</option>';
    }
}

if (isset($_POST['crear'])) {
    // Verificar que los campos obligatorios no estén vacíos
    if (empty($_POST['planta']) || empty($_POST['aula']) || empty($_POST['descripcion'])) {
        echo "<script type='text/javascript'>alert('Por favor, complete los campos obligatorios: Planta, Aula y Descripción.')</script>";
    } else {
        // Los campos no están vacíos, procedemos
        $planta = htmlspecialchars($_POST['planta']);
        $aula = htmlspecialchars($_POST['aula']);
        $descripcion = htmlspecialchars($_POST['descripcion']);

        // Verificar si la planta existe
        $queryPlanta = "SELECT id FROM plantas WHERE planta = '$planta'";
        $resultadoPlanta = mysqli_query($conn, $queryPlanta);
        if (!$resultadoPlanta || mysqli_num_rows($resultadoPlanta) == 0) {
            echo "<script type='text/javascript'>alert('La planta seleccionada no existe.')</script>";
            exit;
        }
        $filaPlanta = mysqli_fetch_assoc($resultadoPlanta);
        $planta_id = $filaPlanta['id'];

        // Verificar si el aula existe
        $queryAula = "SELECT id FROM aulas WHERE aula = '$aula'";
        $resultadoAula = mysqli_query($conn, $queryAula);
        if (!$resultadoAula || mysqli_num_rows($resultadoAula) == 0) {
            echo "<script type='text/javascript'>alert('El aula seleccionada no existe.')</script>";
            exit;
        }
        $filaAula = mysqli_fetch_assoc($resultadoAula);
        $aula_id = $filaAula['id'];

        // Obtener fecha actual
        $fecha_alta = date('Y-m-d'); // Formato: YYYY-MM-DD

        // Ponemos la fecha de revisión y solución en blanco
        $fecha_rev = "";
        $fecha_sol = "";

        $comentario = htmlspecialchars($_POST['comentario']);

        // Insertar en la tabla incidencias utilizando los IDs de planta y aula
        $queryIncidencias = "INSERT INTO incidencias (planta_id, aula_id, descripcion, fecha_alta, fecha_rev, fecha_sol, comentario, usuario_id) VALUES ('$planta_id', '$aula_id', '$descripcion', '$fecha_alta', '$fecha_rev', '$fecha_sol', '$comentario', '$usuario_id')";
        
        $resultIncidencias = mysqli_query($conn, $queryIncidencias);
        
        if (!$resultIncidencias) {
            echo "Algo ha ido mal añadiendo la incidencia: " . mysqli_error($conn);
        } else {
            echo "<script type='text/javascript'>alert('¡Incidencia añadida con éxito!')</script>";
        }
    }
}

function USA($fecha)
{
    // Convierte la fecha de formato europeo (dd-mm-yyyy) a formato americano (yyyy-mm-dd)
    $partes = explode('-', $fecha);
    if (count($partes) == 3) {
        $fecha_americano = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
        return $fecha_americano;
    }
    return $fecha;
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Función para mostrar las aulas según la planta seleccionada
function mostraraula() {
    var plantaSeleccionada = $("#planta").val(); // Obtener el valor seleccionado del desplegable de plantas

    // Limpiar y llenar el desplegable de aulas con las aulas correspondientes a la planta seleccionada
    $('#aula').empty();
    $.each(<?php echo json_encode($aulayplanta); ?>[plantaSeleccionada], function (index, aula) {
        $('#aula').append('<option value="' + aula + '">' + aula + '</option>');
    });
}

$(document).ready(function () {
    // Función para cambiar las aulas dependiendo de la planta
    $("#planta").change(mostraraula);

    // Recargar mostraraula tras cada cambio
    mostraraula();
});
</script>

<section>
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12 col-sm-8 col-md-8 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <img src="../IESlogo.jpg" alt="IES Antonio Machado" style="display: block; margin: 0 auto; margin-bottom: 20px;">
                    <h2 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">AÑADIR INCIDENCIA</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                    <div class="form-group text-center d-flex justify-content-around">
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0" style="margin-right: 5px"><label for="planta" class="form-label">Planta:</label></h4>
                            <select name="planta" id="planta" class="form-select custom-select" style="width: auto;">
                                <option value="Baja">Baja</option>
                                <option value="Primera">Primera</option>
                                <option value="Segunda">Segunda</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <h4 class="mb-0" style="margin-right: 5px"><label for="aula" class="form-label">Aula:</label></h4>
                            <select name="aula" class="form-select custom-select" id="aula" style="width: auto;"></select>
                        </div>
                    </div>
                        <div class="form-group text-center">
                            <h4><label for="descripcion" class="form-label">Descripcion</label></h4>
                            <textarea name="descripcion" class="form-control" cols="40" rows="5"></textarea>
                        </div>
                        <div class="text-center mt-3" id="error"></div>
                        <div class="text-center mt-3">
                            <div class="form-group text-center">
                                <input type="submit"  name="crear" class="btn btn-primary mt-2" value="Añadir">
                            </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>
</section>
</div>
<div class="container text-center mt-5">
    <a href="incidenciasabiertas.php" class="btn btn-warning mt-5"> Volver </a>
<div>

<?php include "../footer.php" ?>