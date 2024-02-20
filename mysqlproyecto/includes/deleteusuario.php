<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['perfil'] !== 'administrador' || !isset($_SESSION['token'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php";
include "login.php";

$mensaje = ""; // Mensaje para mostrar después de la acción

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    // Tu código para buscar y eliminar al usuario
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    // Obtener el ID del usuario a partir del nombre
    $query_id = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt_id = $conn->prepare($query_id);
    $stmt_id->bind_param("s", $nombre);
    $stmt_id->execute();
    $result_id = $stmt_id->get_result();
    $row_id = $result_id->fetch_assoc();
    $id = $row_id['id'];
    
    // Sentencias para eliminar tablas asociadas al usuario
    $tabledelete = "DELETE FROM incidencias WHERE usuario_id = ?";
    $stmt_tabledelete = $conn->prepare($tabledelete);
    $stmt_tabledelete->bind_param("i", $id);
    $stmt_tabledelete->execute();
    
    // Sentencia para eliminar al usuario
    $userdelete = "DELETE FROM usuarios WHERE id = ?";
    $stmt_userdelete = $conn->prepare($userdelete);
    $stmt_userdelete->bind_param("i", $id);
    $stmt_userdelete->execute();
    
    $mensaje = "Usuario '$nombre' eliminado exitosamente.";
}
?>

<section>
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 m-auto">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Buscar y Eliminar Usuario</h2>
                        <form id="searchForm">
                            <div class="form-group">
                                <label for="nombre">Nombre de Usuario:</label>
                                <!-- Desplegable para mostrar todos los usuarios (excluyendo administradores) -->
                                <select class="form-control" id="nombre" name="nombre" required>
                                    <?php
                                    $query = "SELECT id, usuario FROM usuarios WHERE perfil != 'administrador'";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['usuario']}'>{$row['usuario']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="button" id="confirmarEliminar" class="btn btn-primary">Eliminar</button>
                            </div>
                        </form>
                        <div id="resultadoBusqueda" class="mt-4"><?php echo $mensaje; ?></div>
                        <div id="confirmacion" class="mt-4"></div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // AJAX para buscar el usuario y mostrar mensaje de confirmación
    $('#confirmarEliminar').click(function() {
        // Obtener el nombre del usuario seleccionado
        var nombreUsuario = $("#nombre").val();
        // Mostrar mensaje de confirmación
        $('#confirmacion').html("<p>¿Estás seguro de que deseas eliminar al usuario " + nombreUsuario + "? Esta acción es irrecuperable.</p><center><button id='eliminarUsuario' class='btn btn-danger'>Eliminar</button></center>");
    });

    // AJAX para eliminar el usuario
    $(document).on('click', '#eliminarUsuario', function() {
        // Obtener el nombre del usuario
        var nombreUsuario = $("#nombre").val();
        // Confirmación final del usuario
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar al usuario " + nombreUsuario + "? Esta acción es irrecuperable.");
        if (confirmacion) {
            // Eliminar al usuario
            $.ajax({
                type: 'POST',
                url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>',
                data: {nombre: nombreUsuario}, // Enviar el nombre del usuario para eliminar
                success: function(response) {
                    // Mostrar mensaje de éxito y eliminar el formulario
                    $('#confirmacion').html("<center><p>Usuario '" + nombreUsuario + "' eliminado exitosamente.</p></center>");
                }
            });
        }
    });
</script>

<?php include "../footer.php" ?>