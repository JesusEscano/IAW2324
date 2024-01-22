<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php";

$usuario_activo = $_SESSION['user'];
$query_usuario = "SELECT id FROM usuarios WHERE usuario = '$usuario_activo'";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $usuario_id = $fila_usuario['id'];
} else {
    die("Error al obtener el ID del usuario: " . mysqli_error($conn));
}

if (isset($_POST['crear'])) {
    // Verificar que los campos obligatorios no estén vacíos
    if (empty($_POST['planta']) || empty($_POST['aula']) || empty($_POST['descripcion'])) {
        echo "<script type='text/javascript'>alert('Por favor, complete los campos obligatorios: Planta, Aula y Descripción.')</script>";
    } else {
        // Los campos no están vacíos, proceder con la inserción
        $planta = htmlspecialchars($_POST['planta']);
        $aula = htmlspecialchars($_POST['aula']);
        $descripcion = htmlspecialchars($_POST['descripcion']);

        // Obtener fecha actual
        $fecha_alta = date('Y-m-d'); // Formato: YYYY-MM-DD

        // Inicializar fecha de revisión y solución en blanco
        $fecha_rev = "";
        $fecha_sol = "";

        $comentario = htmlspecialchars($_POST['comentario']);

        // Insertar en la tabla plantas
        $queryPlanta = "INSERT INTO plantas (planta) VALUES ('$planta')";
        $resultadoPlanta = mysqli_query($conn, $queryPlanta);

        if (!$resultadoPlanta) {
        echo "Error al insertar en la tabla plantas: " . mysqli_error($conn);
        }

        // Obtener el ID de la planta recién insertada
        $planta_id = mysqli_insert_id($conn);
        
        // Insertar en la tabla aulas
        $queryAula = "INSERT INTO aulas (aula) VALUES ('$aula')";
        $resultadoAula = mysqli_query($conn, $queryAula);

        if (!$resultadoAula) {
        echo "Error al insertar en la tabla aulas: " . mysqli_error($conn);
        }

        // Obtener el ID del aula recién insertada
        $aula_id = mysqli_insert_id($conn);

        // Insertar en la tabla incidencias utilizando los IDs de planta y aula
        $queryIncidencias = "INSERT INTO incidencias (planta, aula, descripcion, fecha_alta, fecha_rev, fecha_sol, comentario, usuario_id) VALUES ('$planta_id', '$aula_id', '$descripcion', '$fecha_alta', '$fecha_rev', '$fecha_sol', '$comentario', '$usuario_id')";

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

<script>
// indicar fecha actual
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

        $(document).ready(function () {
        // Seleccionar planta por defecto
        splanta('baja');

        // Función para mostrar aulas según la planta seleccionada
        function mostraraula() {
            var haelegidoplanta = $("input[name='planta']:checked").val();
            var aulas = aulaxplanta[haelegidoplanta] || [];
            $("#aula").empty();

            if (haelegidoplanta) {
                $.each(aulas, function (index, aula) {
                    $("#aula").append('<option value="' + aula + '">' + aula + '</option>');
                });
                $("#aula").prop("disabled", false);
            } else {
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

        // Vincular la función de validación al envío del formulario
        $("form").submit(function (event) {
            // Evitar que el formulario se envíe si la validación no pasa
            if (!validarFormulario()) {
                event.preventDefault();
            }
        });
        $(document).ready(function () {
        // Seleccionar planta por defecto
        splanta('Baja');

        // Función para mostrar aulas según la planta seleccionada
        function mostraraula() {
            var haelegidoplanta = $("input[name='planta']:checked").val();
            var aulas = aulaxplanta[haelegidoplanta] || [];
            $("#aula").empty();

            if (haelegidoplanta) {
                $.each(aulas, function (index, aula) {
                    $("#aula").append('<option value="' + aula + '">' + aula + '</option>');
                });
                $("#aula").prop("disabled", false);
            } else {
                $("#aula").append('<option disabled selected value="" style="text-align:center;">Por favor, seleccione la planta</option>');
                $("#aula").prop("disabled", true);
            }
        }

        // Cambiar las aulas
        $("input[name='planta']").change(function () {
            mostraraula();
        });

        // Vincular la función de validación al envío del formulario
        $("form").submit(function (event) {
            // Obtener valores de los campos
            var planta = $("input[name='planta']:checked").val();
            var aula = $("#aula").val();
            var descripcion = $("textarea[name='descripcion']").val();

            // Validar que los campos obligatorios estén completos
            if (!planta || !aula || !descripcion) {
                // Mostrar el mensaje de error
                $("#error").html("<p style='color: red;'>Por favor, complete los campos obligatorios: Planta, Aula y Descripción.</p>");
                event.preventDefault(); // Evitar que el formulario se envíe
            } else {
                // Limpiar mensaje de error si todo está bien
                $("#error").empty();
            }
        });
    });
    });
    })

</script>
<section>
<div class="container mt-5 pt-5">
    <div class="row">
    <div class="col-12 col-sm-8 col-md-6 m-auto">
    <div class="card border-0 shadow">
    <div class="card-body">
    <img src="../IESlogo.jpg" alt="IES Antonio Machado" style="display: block; margin: 0 auto; margin-bottom: 20px;">
    <h2 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">AÑADIR INCIDENCIA</h2>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

<div class="form-group text-center d-flex justify-content-around">
    <div class="d-flex align-items-center">
        <h4 class="mb-0" style="margin-right: 5px"><label for="planta" class="form-label">Planta:</label></h4>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="planta" id="planta_baja" value="Baja" onclick="splanta('Baja') required">
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
</div>
</section>
</div>
  </div>
  <div class="container text-center mt-5">
   <a href="incidenciasabiertas.php" class="btn btn-warning mt-5"> Volver </a>
  <div>
<?php include "../footer.php" ?>