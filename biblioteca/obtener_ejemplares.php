<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos

// Consulta SQL para obtener todos los ejemplares con la ruta de la imagen del libro
$sql = "SELECT ejemplares.id_ejemplar, libros.nombre_libro, GROUP_CONCAT(autores.nombre_autor SEPARATOR ', ') AS autores, ejemplares.estado
        FROM ejemplares
        INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
        INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
        INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
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

mysqli_close($conn);
?>