<?php
// Archivo de conexión a la base de datos, cambiar en entrega
include_once 'bd.php';

// Verificar si se recibió el parámetro de búsqueda
if(isset($_POST['busqueda'])) {
    // Limpiar y escapar el texto de búsqueda para evitar la inyección SQL
    $busqueda = mysqli_real_escape_string($conn, $_POST['busqueda']);

    // Consulta SQL para buscar libros por título o autor
    $sql = "SELECT libros.id_libro, libros.nombre_libro, autores.nombre_autor, libros.ano_publicacion, libros.imagen_libro 
            FROM libros
            INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro
            INNER JOIN autores ON autor_libro.id_autor = autores.id_autor
            WHERE libros.nombre_libro LIKE '%$busqueda%' OR autores.nombre_autor LIKE '%$busqueda%'
            GROUP BY libros.id_libro";

    // Ejecutar la consulta
    $result = mysqli_query($conn, $sql);

    // Verificar si se encontraron resultados
    if(mysqli_num_rows($result) > 0) {
        // Mostrar resultados en formato de tabla
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td><img src='media/{$row['imagen_libro']}' class='imagen-libro'></td>";
            echo "<td>{$row['nombre_libro']}</td>";
            echo "<td>{$row['nombre_autor']}</td>";
            echo "<td>{$row['ano_publicacion']}</td>";
            echo "<td><a href='editarlibro.php?id=" . $row['id_libro'] . "' class='btn btn-primary'>Editar</a></td>";
            echo "</tr>";
        }
    } else {
        // Mostrar un mensaje si no se encontraron resultados
        echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
    }

    // Liberar el resultado y cerrar la conexión
    mysqli_free_result($result);
    mysqli_close($conn);
} else {
    // Si no se recibió el parámetro de búsqueda, mostrar un mensaje de error
    echo "<tr><td colspan='5'>Error: Parámetro de búsqueda no recibido</td></tr>";
}
?>