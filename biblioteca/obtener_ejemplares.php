<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos, cámbialo si lo mueves
// Este es el código para actualizar la lista de ejemplares al realizar una búsqueda
// Verificar si se envió la solicitud de edición de estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_ejemplar']) && isset($_POST['nuevoEstado'])) {
    $id_ejemplar = intval($_POST['id_ejemplar']);
    $nuevoEstado = $_POST['nuevoEstado'];

    // Actualizar el estado del ejemplar en la base de datos
    $sql_update_estado = "UPDATE ejemplares SET estado = ? WHERE id_ejemplar = ?";
    $stmt = mysqli_prepare($conn, $sql_update_estado);
    mysqli_stmt_bind_param($stmt, "si", $nuevoEstado, $id_ejemplar);
    if (mysqli_stmt_execute($stmt)) {
        // Enviar una respuesta al cliente
        echo "success";
        exit(); 
    } else {
        // Enviar un mensaje de error al cliente
        echo "error";
        exit(); 
    }
}

// Verificar si se envió la solicitud de añadir otro ejemplar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_libro'])) {
    $id_libro = intval($_POST['id_libro']);

    // Insertar otro ejemplar del mismo libro en la base de datos
    $sql_insert_ejemplar = "INSERT INTO ejemplares (id_libro, estado) VALUES (?, 'Disponible')";
    $stmt = mysqli_prepare($conn, $sql_insert_ejemplar);
    mysqli_stmt_bind_param($stmt, "i", $id_libro);
    if (mysqli_stmt_execute($stmt)) {
        // Enviar una respuesta al cliente
        echo "success";
        exit(); 
    } else {
        // Enviar un mensaje de error al cliente
        echo "error";
        exit(); 
    }
}

// Consulta SQL para obtener todos los ejemplares
$sql = "SELECT ejemplares.id_ejemplar, libros.id_libro, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado
        FROM ejemplares
        INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
        INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
        INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
        GROUP BY ejemplares.id_ejemplar
        ORDER BY libros.nombre_libro";

$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr style='vertical-align: middle;'>";
        echo "<td>" . $fila['id_ejemplar'] . "</td>";
        echo "<td>" . $fila['nombre_libro'] . "</td>";
        echo "<td>" . $fila['autores'] . "</td>";
        // Desplegable para seleccionar el estado
        echo "<td style='min-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>";
        echo "<select class='form-select text-" . ($fila['estado'] == 'Disponible' ? 'success' : ($fila['estado'] == 'Alquilado' ? 'danger' : 'secondary')) . "' id='estado_ejemplar_" . $fila['id_ejemplar'] . "'>";
        echo "<option value='Disponible'" . ($fila['estado'] == 'Disponible' ? ' selected' : '') . ">Disponible</option>";
        echo "<option value='Alquilado'" . ($fila['estado'] == 'Alquilado' ? ' selected' : '') . ">Alquilado</option>";
        echo "<option value='Retirado'" . ($fila['estado'] == 'Retirado' ? ' selected' : '') . ">Retirado</option>";
        echo "</select>";
        echo "</td>";
        // Botón para editar el estado
        echo "<td style='min-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>";
        echo "<button class='btn btn-primary' onclick='editarEstado(" . $fila['id_ejemplar'] . ")'>Editar</button>";
        // Botón para añadir otro ejemplar del mismo libro
        echo "<a class='bi bi-plus-square-fill ms-1' style='font-size: 2rem;' href='añadir_ejemplar.php?id_libro=" . $fila['id_libro'] . "'></a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No se encontraron ejemplares</td></tr>";
}

mysqli_close($conn);
?>

<script>
// Función para enviar la solicitud de edición de estado al servidor
function editarEstado(id_ejemplar) {
    var nuevoEstado = document.getElementById('estado_ejemplar_' + id_ejemplar).value;
    
    // Realizar una solicitud AJAX para actualizar el estado del ejemplar
    $.ajax({
        url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
        method: 'POST',
        data: { id_ejemplar: id_ejemplar, nuevoEstado: nuevoEstado },
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response === 'success') {
                alert('Estado actualizado correctamente.');
                // Recargar la página para actualizar la tabla de ejemplares
                location.reload();
            } else {
                alert('Se produjo un error al actualizar el estado.');
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores de la solicitud AJAX
            console.error(error);
            alert('Se produjo un error al actualizar el estado.');
        }
    });
}

// Función para añadir otro ejemplar de ese mismo libro
function añadirEjemplar(id_libro) {
    // Realizar una solicitud AJAX para añadir otro ejemplar del mismo libro
    $.ajax({
        url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
        method: 'POST',
        data: { id_libro: id_libro },
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response === 'success') {
                alert('Ejemplar añadido correctamente.');
                // Recargar la página para actualizar la tabla de ejemplares
                location.reload();
            } else {
                alert('Se produjo un error al añadir el ejemplar.');
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores de la solicitud AJAX
            console.error(error);
            alert('Se produjo un error al añadir el ejemplar.');
        }
    });
}
</script>