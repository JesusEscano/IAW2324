<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si se recibió el parámetro de búsqueda
if(isset($_POST['busqueda'])) {
    // Limpiar y escapar el texto de búsqueda para evitar inyección SQL
    $busqueda = mysqli_real_escape_string($conn, $_POST['busqueda']);

    // Consulta SQL para buscar ejemplares por título o autor
    $sql = "SELECT ejemplares.id_ejemplar, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado
            FROM ejemplares
            INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
            INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
            INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
            WHERE libros.nombre_libro LIKE '%$busqueda%' OR autores.nombre_autor LIKE '%$busqueda%'
            GROUP BY ejemplares.id_ejemplar";

    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . $fila['id_ejemplar'] . "</td>";
            echo "<td>" . $fila['nombre_libro'] . "</td>";
            echo "<td>" . $fila['autores'] . "</td>";
            echo "<td>" . $fila['estado'] . "</td>";
            echo "<td><a href='editarejemplar.php?id=" . $fila['id_ejemplar'] . "' class='btn btn-primary'>Editar</a></td>";
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

mysqli_close($conn);
?>