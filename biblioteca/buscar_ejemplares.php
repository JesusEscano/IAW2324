<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si se recibió el parámetro de búsqueda
if(isset($_POST['busqueda'])) {
    // Limpiar y escapar el texto de búsqueda para evitar inyección SQL
    $busqueda = mysqli_real_escape_string($conn, $_POST['busqueda']);

    // Consulta SQL para buscar ejemplares por título o autor
    $sql = "SELECT ejemplares.id_ejemplar, libros.id_libro, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado
            FROM ejemplares
            INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
            INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
            INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
            WHERE libros.nombre_libro LIKE '%$busqueda%' OR autores.nombre_autor LIKE '%$busqueda%' OR ejemplares.estado LIKE '%$busqueda%'
            GROUP BY ejemplares.id_ejemplar";

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
            // Contenedor para los botones de acción
            echo "<td>";
            echo "<div class='d-flex'>";
            // Botón para editar el estado
            echo "<button class='btn btn-primary me-1' onclick='editarEstado(" . $fila['id_ejemplar'] . ")'>Editar</button>";
            // Botón para añadir otro ejemplar del mismo libro
            echo "<a class='bi bi-plus-square-fill ms-1' style='font-size: 2rem;' href='añadir_ejemplar.php?id_libro=" . $fila['id_libro'] . "'></a>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No se encontraron ejemplares</td></tr>";
    }
    mysqli_free_result($resultado);
} else {
    // Si no se recibió el parámetro de búsqueda, mostrar un mensaje de error
    echo "<tr><td colspan='6'>Error: Parámetro de búsqueda no recibido</td></tr>";
}

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
        exit(); // Terminar el script
    } else {
        // Enviar un mensaje de error al cliente
        echo "error";
        exit(); // Terminar el script
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
        exit(); // Terminar el script
    } else {
        // Enviar un mensaje de error al cliente
        echo "error";
        exit(); // Terminar el script
    }
}

mysqli_close($conn);
?>