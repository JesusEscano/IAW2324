<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Definir variables para la paginación
$libros_por_pagina = 10; // Número de libros por página

// Obtener la cadena de búsqueda, si está presente
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

// Consulta para obtener los libros de la página actual, considerando la cadena de búsqueda
$sql_libros = "SELECT libros.id_libro, nombre_libro, 
               GROUP_CONCAT(DISTINCT nombre_autor SEPARATOR ', ') AS autores, 
               (SELECT COUNT(*) FROM ejemplares WHERE ejemplares.id_libro = libros.id_libro AND ejemplares.estado = 'Disponible') AS ejemplares_disponibles, 
               imagen_libro, tema 
               FROM libros 
               INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
               INNER JOIN autores ON autor_libro.id_autor = autores.id_autor ";

// Si hay una cadena de búsqueda, agregar condiciones a la consulta SQL
if (!empty($busqueda)) {
    $sql_libros .= "WHERE nombre_libro LIKE '%$busqueda%' OR nombre_autor LIKE '%$busqueda%' OR tema LIKE '%$busqueda%' ";
} else {
    // Si no hay una cadena de búsqueda, incluir todos los libros
    include_once 'FE_todos_libros.php';
    exit(); // Salir del script después de incluir el archivo
}

// Obtener el número total de libros que coinciden con la búsqueda actual
$sql_total_libros = "SELECT COUNT(*) AS total FROM ($sql_libros) AS subconsulta";
$resultado_total_libros = mysqli_query($conn, $sql_total_libros);
$fila_total_libros = mysqli_fetch_assoc($resultado_total_libros);
$total_libros = $fila_total_libros['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_libros / $libros_por_pagina);

// Obtener el número de página actual
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$pagina_actual = min($pagina_actual, $total_paginas); // Asegurar que la página actual no supere el número total de páginas

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $libros_por_pagina;

// Consulta para obtener los libros de la página actual, considerando la cadena de búsqueda y la paginación
$sql_libros .= "GROUP BY libros.id_libro, nombre_libro, imagen_libro, tema 
                ORDER BY nombre_libro ASC 
                LIMIT $offset, $libros_por_pagina";

$resultado_libros = mysqli_query($conn, $sql_libros);

// Comprobar si se encontraron resultados
if(mysqli_num_rows($resultado_libros) > 0) {
    // Mostrar la tabla con los resultados de la búsqueda
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Imagen</th>';
    echo '<th>Título</th>';
    echo '<th>Autor</th>';
    echo '<th>Tema</th>';
    echo '<th>Disponibles</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    // Generar las filas de la tabla con los resultados de la búsqueda
    while($fila = mysqli_fetch_assoc($resultado_libros)) {
        $href = 'reservarlibro.php?id=' . $fila['id_libro'];
        echo '<tr class="clickable-row" data-href="' . $href . '">';
        echo '<td>' . ($fila['imagen_libro'] ? '<a href="' . $href . '"><img src="media/' . $fila['imagen_libro'] . '" class="imagen-libro" alt="Imagen del libro"></a>' : '') . '</td>';
        echo '<td><a href="' . $href . '">' . $fila['nombre_libro'] . '</a></td>';
        echo '<td class="ellipsis" data-original-text="' . htmlspecialchars($fila['autores']) . '"><a href="' . $href . '">' . htmlspecialchars($fila['autores']) . '</a></td>';
        echo '<td><a href="' . $href . '">' . $fila['tema'] . '</a></td>';
        echo '<td><a href="' . $href . '">' . $fila['ejemplares_disponibles'] . '</a></td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
} else {
    // Si no se encontraron resultados, mostrar un mensaje de error
    echo '<p>No se encontraron resultados</p>';
}

// Liberar resultados
mysqli_free_result($resultado_libros);

// Cerrar la conexión
mysqli_close($conn);

// Controles de paginación
echo '<div class="pagination">';
if ($total_paginas > 1) {
    if ($pagina_actual > 1) {
        echo '<a href="?pagina=' . ($pagina_actual - 1) . '" class="anterior">Anterior</a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo '<a ' . (($i == $pagina_actual) ? 'class="active"' : '') . ' href="?pagina=' . $i . '">' . $i . '</a>';
    }
    if ($pagina_actual < $total_paginas) {
        echo '<a href="?pagina=' . ($pagina_actual + 1) . '" class="siguiente">Siguiente</a>';
    }
}
echo '</div>';
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var rows = document.querySelectorAll('.clickable-row');
        rows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location = row.getAttribute('data-href');
            });
        });
    })
</script>