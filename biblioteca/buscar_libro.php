<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Obtener el término de búsqueda
$titulo = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Consultar la base de datos para obtener los libros que coincidan con el título
$sql = "SELECT id_libro, nombre_libro FROM libros WHERE nombre_libro LIKE '%$titulo%'";
$resultado = mysqli_query($conn, $sql);

// Generar la tabla con los resultados
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo '<tr>';
        echo '<td>' . $fila['id_libro'] . '</td>';
        echo '<td>' . $fila['nombre_libro'] . '</td>';
        echo '<td><button class="btn btn-primary seleccionar-libro" data-id="' . $fila['id_libro'] . '" data-nombre_libro="' . $fila['nombre_libro'] . '">Seleccionar</button></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">No se encontraron resultados</td>';
}

// Cerrar la conexión
mysqli_close($conn);
?>