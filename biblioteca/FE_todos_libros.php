<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Configuración para paginación
$libros_por_pagina = 10; // Número de libros por página

// Obtener el número total de libros
$sql_total_libros = "SELECT COUNT(*) AS total FROM libros";
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

// Consulta para obtener los libros de la página actual
$sql_libros = "SELECT libros.id_libro, nombre_libro, 
               GROUP_CONCAT(DISTINCT nombre_autor SEPARATOR ', ') AS autores, 
               (SELECT COUNT(*) FROM ejemplares WHERE ejemplares.id_libro = libros.id_libro AND ejemplares.estado = 'Disponible') AS ejemplares_disponibles, 
               imagen_libro 
               FROM libros 
               INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
               INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
               GROUP BY libros.id_libro, nombre_libro, imagen_libro 
               ORDER BY nombre_libro ASC 
               LIMIT $offset, $libros_por_pagina";

$resultado_libros = mysqli_query($conn, $sql_libros);

// Mostrar la tabla con los resultados de los libros
if(mysqli_num_rows($resultado_libros) > 0) {
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Imagen</th>';
    echo '<th>Título</th>';
    echo '<th>Autor</th>';
    echo '<th>Ejemplares Disponibles</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while($fila = mysqli_fetch_assoc($resultado_libros)) {
        echo '<tr>';
        echo '<td>' . ($fila['imagen_libro'] ? '<img src="media/' . $fila['imagen_libro'] . '" class="imagen-libro" alt="Imagen del libro">' : '') . '</td>';
        echo '<td>' . $fila['nombre_libro'] . '</td>';
        echo '<td class="ellipsis" data-original-text="' . htmlspecialchars($fila['autores']) . '">' . htmlspecialchars($fila['autores']) . '</td>';
        echo '<td>' . $fila['ejemplares_disponibles'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No se encontraron libros</p>';
}

// Mostrar los controles de paginación
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

// Liberar resultados
mysqli_free_result($resultado_total_libros);
mysqli_free_result($resultado_libros);

// Cerrar la conexión
mysqli_close($conn);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.innerWidth <= 700) { // Ejecutar solo en pantallas pequeñas
            var ellipsisCells = document.querySelectorAll('.ellipsis');
            ellipsisCells.forEach(function(cell) {
                var text = cell.innerText;
                var originalText = cell.getAttribute('data-original-text');
                if (text.length > 20) {
                    cell.innerText = text.substring(0, 20) + '...';
                    cell.addEventListener('click', function() {
                        var temp = cell.innerText;
                        cell.innerText = originalText;
                        originalText = temp;
                    });
                }
            });
        }
    });
</script>