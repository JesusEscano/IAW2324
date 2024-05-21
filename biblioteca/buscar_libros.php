<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos

$busqueda = isset($_POST['busqueda']) ? $conn->real_escape_string($_POST['busqueda']) : "";
$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$libros_por_pagina = 10;
$offset = ($pagina - 1) * $libros_por_pagina;

// Obtener el total de libros que coinciden con la búsqueda
$sql_total = "SELECT COUNT(*) as total FROM libros 
              INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
              INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
              WHERE nombre_libro LIKE '%$busqueda%' OR nombre_autor LIKE '%$busqueda%'";
$resultado_total = mysqli_query($conn, $sql_total);
$fila_total = mysqli_fetch_assoc($resultado_total);
$total_libros = $fila_total['total'];
$total_paginas = ceil($total_libros / $libros_por_pagina);

// Obtener los libros que coinciden con la búsqueda en la página actual
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

// Generar los controles de paginación solo si hay más de una página de resultados
$pagination = '';
if ($total_paginas > 1) {
    $pagination .= '<div class="pagination">';
    if ($pagina > 1) {
        $pagination .= '<a href="#" class="anterior" data-page="' . ($pagina - 1) . '">Anterior</a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        $pagination .= '<a href="#" ' . (($i == $pagina) ? 'class="active"' : '') . ' data-page="' . $i . '">' . $i . '</a>';
    }
    if ($pagina < $total_paginas) {
        $pagination .= '<a href="#" class="siguiente" data-page="' . ($pagina + 1) . '">Siguiente</a>';
    }
    $pagination .= '</div>';
}

echo json_encode(['libros' => $libros, 'pagination' => $pagination]);

mysqli_close($conn);
?>