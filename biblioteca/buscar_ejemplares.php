<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Definir variables para la paginación
$ejemplares_por_pagina = 10;
$pagina_actual = isset($_POST['pagina']) ? $_POST['pagina'] : 1;

// Verificar si se recibió el parámetro de búsqueda
if(isset($_POST['busqueda'])) {
    // Limpiar y escapar el texto de búsqueda para evitar inyección SQL
    $busqueda = mysqli_real_escape_string($conn, $_POST['busqueda']);

    // Calcular el offset para la consulta SQL
    $offset = ($pagina_actual - 1) * $ejemplares_por_pagina;

    // Consulta SQL para obtener el número total de filas sin aplicar la limitación de la paginación
    $sql_count = "SELECT COUNT(*) AS total_filas
                  FROM ejemplares
                  INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
                  INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
                  INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
                  WHERE libros.nombre_libro LIKE '%$busqueda%' OR autores.nombre_autor LIKE '%$busqueda%' OR ejemplares.estado LIKE '%$busqueda%'
                  GROUP BY ejemplares.id_ejemplar";

    // Ejecutar la consulta SQL para obtener el número total de filas
    $result_count = mysqli_query($conn, $sql_count);
    $total_filas = mysqli_num_rows($result_count);

    // Calcular el número total de páginas
    $total_paginas = ceil($total_filas / $ejemplares_por_pagina);

    // Consulta SQL para buscar ejemplares por título, autor o estado
    $sql = "SELECT ejemplares.id_ejemplar, libros.id_libro, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado, ejemplares.estanteria
            FROM ejemplares
            INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
            INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
            INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
            WHERE libros.nombre_libro LIKE '%$busqueda%' OR autores.nombre_autor LIKE '%$busqueda%' OR ejemplares.estado LIKE '%$busqueda%'
            GROUP BY ejemplares.id_ejemplar
            ORDER BY libros.nombre_libro
            LIMIT ?, ?";

    // Preparar la consulta SQL
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
            $ejemplares .= "<td>" . $fila['estanteria'] . "</td>"; // Nueva columna de estantería
            // Desplegable para seleccionar el estado
            $ejemplares .= "<td style='min-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>";
            $ejemplares .= "<select class='form-select text-" . ($fila['estado'] == 'Disponible' ? 'success' : ($fila['estado'] == 'Alquilado' ? 'danger' : 'secondary')) . "' id='estado_ejemplar_" . $fila['id_ejemplar'] . "'>";
            $ejemplares .= "<option value='Disponible'" . ($fila['estado'] == 'Disponible' ? ' selected' : '') . ">Disponible</option>";
            $ejemplares .= "<option value='Alquilado'" . ($fila['estado'] == 'Alquilado' ? ' selected' : '') . ">Alquilado</option>";
            $ejemplares .= "<option value='Retirado'" . ($fila['estado'] == 'Retirado' ? ' selected' : '') . ">Retirado</option>";
            $ejemplares .= "</select>";
            $ejemplares .= "</td>";
            // Contenedor para los botones de acción
            $ejemplares .= "<td>";
            $ejemplares .= "<div class='d-flex'>";
            // Botón para editar el estado
            $ejemplares .= "<button class='btn btn-primary me-1' onclick='editarEstado(" . $fila['id_ejemplar'] . ")'>Editar</button>";
            // Botón para añadir otro ejemplar del mismo libro
            $ejemplares .= "<a class='bi bi-plus-square-fill ms-1' style='font-size: 2rem;' href='añadir_ejemplar.php?id_libro=" . $fila['id_libro'] . "'></a>";
            $ejemplares .= "</div>";
            $ejemplares .= "</td>";
            $ejemplares .= "</tr>";
        }
    } else {
        $ejemplares = "<tr><td colspan='6'>No se encontraron ejemplares</td></tr>";
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

    // Devolver los ejemplares y la paginación como respuesta
    echo json_encode(['ejemplares' => $ejemplares, 'pagination' => $pagination]);

} else {
    // Si no se recibió el parámetro de búsqueda, mostrar un mensaje de error
    echo "<tr><td colspan='6'>Error: Parámetro de búsqueda no recibido</td></tr>";
}

mysqli_close($conn);
?>