<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php"; // Agrega un punto y coma aquí

$usuario_activo = $_SESSION['user'];
$query_usuario = "SELECT id FROM usuarios WHERE usuario = '$usuario_activo'";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $usuario_id = $fila_usuario['id'];
} else {
    die("Error al obtener el ID del usuario: " . mysqli_error($conn));
}

?>
<?php 
  if(isset($_POST['crear'])) 
    {
        $planta = htmlspecialchars($_POST['planta']);
        $aula = htmlspecialchars($_POST['aula']);
        $descripcion = htmlspecialchars($_POST['descripcion']);
        $fecha_alta = USA(htmlspecialchars($_POST['fecha_alta']));
        $fecha_rev = USA(htmlspecialchars($_POST['fecha_rev']));
        $fecha_sol = USA(htmlspecialchars($_POST['fecha_sol']));
        $comentario = htmlspecialchars($_POST['comentario']);
        $query = "INSERT INTO incidencias(planta, aula, descripcion, fecha_alta, fecha_rev, fecha_sol, comentario, usuario_id) VALUES('{$planta}','{$aula}','{$descripcion}','{$fecha_alta}','{$fecha_rev}','{$fecha_sol}','{$comentario}','{$usuario_id}')";
        $resultado = mysqli_query($conn,$query);
    
          if (!$resultado) {
              echo "Algo ha ido mal añadiendo la incidencia: ". mysqli_error($conn);
          }
          else
          {
            echo "<script type='text/javascript'>alert('¡Incidencia añadida con éxito!')</script>";
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

<script>
    function hoy() {
    var hoy = new Date();
    var yyyy = hoy.getFullYear();
    var mm = String(hoy.getMonth() + 1).padStart(2, '0');
    var dd = String(hoy.getDate()).padStart(2, '0');

    var fechahoy = dd + '-' + mm + '-' + yyyy;
    document.getElementById('fecha_alta').value = fechahoy;
    document.getElementById('fecha_rev').value = fechahoy;
    document.getElementById('fecha_sol').value = fechahoy;
}

// Poner las fechas en null si son 0s
function ponerhoy() {
    var fecha_rev = document.getElementById('fecha_rev').value;
    var fecha_sol = document.getElementById('fecha_sol').value;

    if (fecha_rev === '00-00-0000' || fecha_rev === '') {
        document.getElementById('fecha_rev').value = null;
    }

    if (fecha_sol === '00-00-0000' || fecha_sol === '') {
        document.getElementById('fecha_sol').value = null;
    }
}

 // Vincular la función para establecer la fecha actual en el clic de Fecha Revisión y Fecha Solución
$("#fecha_rev, #fecha_sol").click(function () {
   ponerhoy();
});
    // Seleccionar planta
    function splanta(planta) {
        document.getElementById('planta').value = planta;
        mostraraula();
    }

    // Poner en ready, marca aulas x planta
    $(document).ready(function () {
        var aulaxplanta = {
            Baja: ["Aula 1", "Aula 2", "Aula 3"],
            Primera: ["Aula 101", "Aula 102", "Aula 103"],
            Segunda: ["Aula 201", "Aula 202", "Aula 203"]
        };

        // Función para actualizar las opciones del desplegable de Aula
        function mostraraula() {
            // Obtener el valor seleccionado de Planta
            var haelegidoplanta = $("input[name='planta']:checked").val();

            // Obtener las aulas correspondientes a la planta seleccionada
            var aulas = aulaxplanta[haelegidoplanta] || [];

            // Limpiar el desplegable
            $("#aula").empty();

            // Si hay una planta seleccionada, añade las aulas posibles
            if (haelegidoplanta) {
                $.each(aulas, function (index, aula) {
                    $("#aula").append('<option value="' + aula + '">' + aula + '</option>');
                });

                // Activar el desplegable
                $("#aula").prop("disabled", false);
            } else {
                // Placeholder si no ha elegido
                $("#aula").append('<option disabled selected value="" style="text-align:center;">Por favor, seleccione la planta</option>');
                $("#aula").prop("disabled", true);
            }
        }

        // Cambiar las aulas
        $("input[name='planta']").change(function () {
            mostraraula();
        });

        // Llamar a la función para que se aplique por si caso no lo ha hecho antes
        mostraraula();

        // Seleccionar planta por defecto
        splanta('baja');
    });
</script>

<h1 class="text-center">Añadir incidencia</h1>
  <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <div class="form-group text-center">
            <h4><label for="planta" class="form-label">Planta</label></h4>
        </div>
        <div class="d-flex justify-content-center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="planta" id="planta_baja" value="Baja" onclick="splanta('Baja')">
                <label class="form-check-label" for="planta_baja">Baja</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="planta" id="planta_primera" value="Primera" onclick="splanta('Primera')">
                <label class="form-check-label" for="planta_primera">Primera</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="planta" id="planta_segunda" value="Segunda" onclick="splanta('Segunda')">
                <label class="form-check-label" for="planta_segunda">Segunda</label>
            </div>
        </div>
        <div class="form-group text-center">
        <h4><label for="aula" class="form-label">Aula</label></h4>
    <select name="aula" class="form-select" id="aula">
    </select>
        </div>
      <div class="form-group text-center">
      <h4><label for="descripcion" class="form-label">Descripcion</label></h4>
        <input type="text" name="descripcion"  class="form-control">
      </div>
      <div class="form-group text-center">
      <h4><label for="fecha_alta" class="form-label">Fecha Alta</label></h4>
        <input type="text" name="fecha_alta"  class="form-control" id="fecha_alta" value="<?php echo date('d-m-y') ?>" readonly>
      </div>
      <div class="form-group text-center">
      <h4><label for="fecha_rev" class="form-label">Fecha Revisión</label></h4>
        <input type="date" name="fecha_rev"  class="form-control">
      </div>
      <div class="form-group text-center">
      <h4><label for="fecha_sol" class="form-label">Fecha Solución</label></h4>
        <input type="date" name="fecha_sol"  class="form-control">
      </div>
      <div class="form-group text-center">
      <h4><label for="comentario" class="form-label">Comentario</label></h4>
        <input type="text" name="comentario"  class="form-control">
      </div>
      <div class="form-group text-center">
        <input type="submit"  name="crear" class="btn btn-primary mt-2" value="Añadir">
      </div>
    </form> 
  </div>
  <div class="container text-center mt-5">
    <a href="home.php" class="btn btn-warning mt-5"> Volver </a>
  <div>
<?php include "../footer.php" ?>