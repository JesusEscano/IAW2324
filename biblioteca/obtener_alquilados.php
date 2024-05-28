<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Consulta para obtener todos los libros alquilados
$sql_alquilados = "SELECT 
                      alquiler.id_alquiler, 
                      libros.nombre_libro, 
                      ejemplares.id_ejemplar, 
                      usuarios.nombre_usuario, 
                      alquiler.fecha_alquiler, 
                      alquiler.fecha_max_alquiler
                  FROM alquiler
                  INNER JOIN ejemplares ON alquiler.id_ejemplar = ejemplares.id_ejemplar
                  INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
                  INNER JOIN usuarios ON alquiler.id_usuario = usuarios.id_usuario
                  WHERE alquiler.situacion = 'Alquilado'
                  AND alquiler.fecha_alquiler IS NOT NULL
                  AND alquiler.fecha_max_alquiler IS NOT NULL
                  AND alquiler.fecha_devolucion IS NULL";

$resultado_alquilados = mysqli_query($conn, $sql_alquilados);

// Obtener los datos de los libros alquilados
$libros_alquilados = [];
while ($row = mysqli_fetch_assoc($resultado_alquilados)) {
    $libros_alquilados[] = $row;
}

echo json_encode($libros_alquilados);

// Cerrar la conexión
mysqli_close($conn);
?>