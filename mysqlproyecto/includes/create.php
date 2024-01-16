<?php session_start()?>
<?php if($_SESSION['user']){
}else{
 header("Location: ../login.php");
}?>
<?php  include "../header.php" ?>
<?php 
  if(isset($_POST['crear'])) 
    {
        $planta = htmlspecialchars($_POST['planta']);
        $aula = htmlspecialchars($_POST['aula']);
        $descripcion = htmlspecialchars($_POST['descripcion']);
        $comentario = htmlspecialchars($_POST['comentario']);
        $fecha_alta = htmlspecialchars($_POST['fecha_alta']);
        $fecha_rev = htmlspecialchars($_POST['fecha_rev']);
        $fecha_sol = htmlspecialchars($_POST['fecha_sol']);
      
        $query= "INSERT INTO incidencias(planta, aula, descripcion, fecha_alta, fecha_rev, fecha_sol, comentario) VALUES('{$planta}','{$aula}','{$descripcion}','{$fecha_alta}','{$fecha_rev}','{$fecha_sol}','{$comentario}')";
        $resultado = mysqli_query($conn,$query);
    
          if (!$resultado) {
              echo "Algo ha ido mal añadiendo la incidencia: ". mysqli_error($conn);
          }
          else
          {
            echo "<script type='text/javascript'>alert('¡Incidencia añadida con éxito!')</script>";
          }         
    }
?>

<script>
    // Función para seleccionar la planta
    function seleccionarPlanta(planta) {
        document.getElementById('planta').value = planta;
        actualizarAulas();
    }

    // Función para establecer la fecha actual en el campo de Fecha Alta
    function establecerFechaActual() {
        var fechaActual = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_alta').value = fechaActual;
    }

    // Función para establecer la fecha actual en el campo de Fecha Revisión y Fecha Solución
    function establecerFechaActualEnCampos() {
        var fechaActual = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_rev').value = fechaActual;
        document.getElementById('fecha_sol').value = fechaActual;
    }

    $(document).ready(function () {
        // Definir un objeto con las aulas correspondientes a cada planta
        var aulasPorPlanta = {
            baja: ["Aula 101", "Aula 102", "Aula 103"],
            primera: ["Aula 201", "Aula 202", "Aula 203"],
            segunda: ["Aula 301", "Aula 302", "Aula 303"]
        };

        // Función para actualizar las opciones del desplegable de Aula
        function actualizarAulas() {
            // Obtener el valor seleccionado de Planta
            var plantaSeleccionada = $("input[name='planta']:checked").val();

            // Obtener las aulas correspondientes a la planta seleccionada
            var aulas = aulasPorPlanta[plantaSeleccionada] || [];

            // Limpiar el desplegable actual
            $("#aula").empty();

            // Si hay una planta seleccionada
            if (plantaSeleccionada) {
                // Añadir las nuevas opciones al desplegable de Aula
                $.each(aulas, function (index, aula) {
                    $("#aula").append('<option value="' + aula + '">' + aula + '</option>');
                });

                // Activar el desplegable
                $("#aula").prop("disabled", false);
            } else {
                // Si no hay planta seleccionada, agregar la opción de placeholder y desactivar el desplegable
                $("#aula").append('<option disabled selected value="" style="text-align:center;">Por favor, seleccione la planta</option>');
                $("#aula").prop("disabled", true);
            }
        }

        // Vincular la función a los cambios en el desplegable de Planta
        $("input[name='planta']").change(function () {
            actualizarAulas();
        });

        // Llamar a la función una vez para inicializar las opciones
        actualizarAulas();

        // Establecer la fecha actual en el campo de Fecha Alta al cargar la página
        establecerFechaActual();

        // Vincular la función para establecer la fecha actual en el clic de Fecha Revisión y Fecha Solución
        $("#fecha_rev, #fecha_sol").click(function () {
        establecerFechaActualEnCampos();
});
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
                <input class="form-check-input" type="radio" name="planta" id="planta_baja" value="baja" onclick="seleccionarPlanta('baja')">
                <label class="form-check-label" for="planta_baja">Baja</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="planta" id="planta_primera" value="primera" onclick="seleccionarPlanta('primera')">
                <label class="form-check-label" for="planta_primera">Primera</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="planta" id="planta_segunda" value="segunda" onclick="seleccionarPlanta('segunda')">
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
        <input type="date" name="fecha_alta"  class="form-control">
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