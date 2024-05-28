<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Consulta para obtener todos los libros devueltos
$sql_devueltos = "SELECT 
                      libros.nombre_libro, 
                      ejemplares.id_ejemplar, 
                      alquiler.fecha_devolucion, 
                      ejemplares.estanteria
                  FROM alquiler
                  INNER JOIN ejemplares ON alquiler.id_ejemplar = ejemplares.id_ejemplar
                  INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
                  WHERE alquiler.situacion = 'Devuelto'
                  AND alquiler.fecha_devolucion IS NOT NULL
                  ORDER BY alquiler.fecha_devolucion DESC
                  LIMIT 1";

$resultado_devueltos = mysqli_query($conn, $sql_devueltos);

// Verificar si la consulta fue exitosa
if (!$resultado_devueltos) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

// Obtener los datos de los libros devueltos
$libros_devueltos = [];
while ($row = mysqli_fetch_assoc($resultado_devueltos)) {
    $libros_devueltos[] = $row;
}

echo json_encode($libros_devueltos);

// Cerrar la conexión
mysqli_close($conn);
?>