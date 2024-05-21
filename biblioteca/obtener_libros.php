<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos

$libros_por_pagina = 10; // Número de libros por página
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : "";

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $libros_por_pagina;

// Consulta SQL para contar el número total de libros
$sql_total = "SELECT COUNT(DISTINCT libros.id_libro) as total FROM libros 
              INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
              INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
              WHERE nombre_libro LIKE '%$busqueda%' OR nombre_autor LIKE '%$busqueda%'";

$resultado_total = mysqli_query($conn, $sql_total);
$fila_total = mysqli_fetch_assoc($resultado_total);
$total_libros = $fila_total['total'];
$total_paginas = ceil($total_libros / $libros_por_pagina);

// Consulta SQL para obtener los libros de la página actual
$sql = "SELECT libros.id_libro, nombre_libro, GROUP_CONCAT(nombre_autor SEPARATOR ', ') AS autores, ano_publicacion, imagen_libro 
        FROM libros 
        INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
        INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
        WHERE nombre_libro LIKE '%$busqueda%' OR nombre_autor LIKE '%$busqueda%'
        GROUP BY libros.id_libro
        ORDER BY nombre_libro ASC 
        LIMIT $offset, $libros_por_pagina";

$resultado = mysqli_query($conn, $sql);

$libros = "";
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros .= "<tr>";
        $libros .= "<td><img src='media/" . $fila['imagen_libro'] . "' alt='Imagen de libro' class='imagen-libro'></td>";
        $libros .= "<td>" . $fila['nombre_libro'] . "</td>";
        $libros .= "<td>" . $fila['autores'] . "</td>";
        $libros .= "<td>" . $fila['ano_publicacion'] . "</td>";
        $libros .= "<td><a href='editarlibro.php?id=" . $fila['id_libro'] . "' class='btn btn-primary'>Editar</a></td>";
        $libros .= "</tr>";
    }
} else {
    $libros .= "<tr><td colspan='5'>No se encontraron libros</td></tr>";
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

echo json_encode(['libros' => $libros, 'pagination' => $pagination]);

mysqli_close($conn);
?>