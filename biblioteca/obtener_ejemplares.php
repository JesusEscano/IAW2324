<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos, cámbialo si lo mueves

// Definir variables para la paginación
$ejemplares_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

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

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual -1) * $ejemplares_por_pagina;

// Consulta SQL para obtener la cantidad total de ejemplares
$sql_total_ejemplares = "SELECT COUNT(*) AS total FROM ejemplares";
$resultado_total = mysqli_query($conn, $sql_total_ejemplares);
$total_ejemplares = mysqli_fetch_assoc($resultado_total)['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_ejemplares / $ejemplares_por_pagina);

// Consulta SQL para obtener los ejemplares de la página actual
$sql = "SELECT ejemplares.id_ejemplar, ejemplares.estanteria, libros.id_libro, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado
        FROM ejemplares
        INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
        INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
        INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
        GROUP BY ejemplares.id_ejemplar
        ORDER BY libros.nombre_libro
        LIMIT ?, ?";
        
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $offset, $ejemplares_por_pagina);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$ejemplares = '';
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $ejemplares .= "<tr style='vertical-align: middle;'>";
        $ejemplares .= "<td>" . $fila['id_ejemplar'] . "</td>";
        $ejemplares .= "<td>" . $fila['nombre_libro'] . "</td>";
        $ejemplares .= "<td>" . $fila['autores'] . "</td>";
        $ejemplares .= "<td>" . $fila['estanteria'] . "</td>";
        // Desplegable para seleccionar el estado
        $ejemplares .= "<td style='min-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>";
        $ejemplares .= "<select class='form-select text-" . ($fila['estado'] == 'Disponible' ? 'success' : ($fila['estado'] == 'Alquilado' ? 'danger' : 'secondary')) . "' id='estado_ejemplar_" . $fila['id_ejemplar'] . "'>";
        $ejemplares .= "<option value='Disponible'" . ($fila['estado'] == 'Disponible' ? ' selected' : '') . ">Disponible</option>";
        $ejemplares .= "<option value='Alquilado'" . ($fila['estado'] == 'Alquilado' ? ' selected' : '') . ">Alquilado</option>";
        $ejemplares .= "<option value='Retirado'" . ($fila['estado'] == 'Retirado' ? ' selected' : '') . ">Retirado</option>";
        $ejemplares .= "</select>";
        $ejemplares .= "</td>";
        // Botón para editar el estado
        $ejemplares .= "<td style='min-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>";
        $ejemplares .= "<button class='btn btn-primary' onclick='editarEstado(" . $fila['id_ejemplar'] . ")'>Editar</button>";
        // Botón para añadir otro ejemplar del mismo libro
        $ejemplares .= "<a class='bi bi-plus-square-fill ms-1' style='font-size: 2rem;' href='añadir_ejemplar.php?id_libro=" . $fila['id_libro'] . "'></a>";
        $ejemplares .= "</td>";
        $ejemplares .= "</tr>";
    }
} else {
    $ejemplares = "<tr><td colspan='5'>No se encontraron ejemplares</td></tr>";
}

// Generar los controles de paginación
$pagination = '<div class="pagination">';
if ($total_paginas > 1) {
    if ($pagina_actual > 1) {
        $pagination .= '<a href="#" class="anterior" data-page="' . ($pagina_actual - 1) . '">Anterior</a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        $pagination .= '<a href="#" ' . (($i == $pagina_actual) ? 'class="active"' : '') . ' data-page="' . $i . '">' . $i . '</a>';
    }
    if ($pagina_actual < $total_paginas) {
        $pagination .= '<a href="#" class="siguiente" data-page="' . ($pagina_actual + 1) . '">Siguiente</a>';
    }
}
$pagination .= '</div>';

echo json_encode(['ejemplares' => $ejemplares, 'pagination' => $pagination]);

mysqli_close($conn);
?>