<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos, cámbialo si lo mueves
// Este es el código para mostrar todos los libros en la lista
// Consulta SQL para obtener todos los libros con la ruta de la imagen del libro
$sql = "SELECT libros.id_libro, nombre_libro, GROUP_CONCAT(nombre_autor SEPARATOR ', ') AS autores, ano_publicacion, imagen_libro FROM libros 
        INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
        INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
        GROUP BY libros.id_libro";

$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td><img src='media/" . $fila['imagen_libro'] . "' alt='Imagen de libro' class='imagen-libro'></td>"; // Mostrar la imagen del libro
        echo "<td>" . $fila['nombre_libro'] . "</td>";
        echo "<td>" . $fila['autores'] . "</td>";
        echo "<td>" . $fila['ano_publicacion'] . "</td>";
        echo "<td><a href='editarlibro.php?id=" . $fila['id_libro'] . "' class='btn btn-primary'>Editar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No se encontraron libros</td></tr>";
}

mysqli_close($conn);
?>