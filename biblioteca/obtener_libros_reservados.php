<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Consulta para obtener todos los libros reservados
$sql_reservados = "SELECT 
                      libros.nombre_libro, 
                      ejemplares.id_ejemplar, 
                      usuarios.nombre_usuario, 
                      alquiler.fecha_reserva, 
                      alquiler.fecha_maxima_reserva
                  FROM alquiler
                  INNER JOIN ejemplares ON alquiler.id_ejemplar = ejemplares.id_ejemplar
                  INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
                  INNER JOIN usuarios ON alquiler.id_usuario = usuarios.id_usuario
                  WHERE alquiler.situacion = 'Reservado'
                  AND alquiler.fecha_reserva IS NOT NULL
                  AND alquiler.fecha_maxima_reserva IS NOT NULL";

$resultado_reservados = mysqli_query($conn, $sql_reservados);

// Obtener los datos de los libros reservados
$libros_reservados = [];
while ($row = mysqli_fetch_assoc($resultado_reservados)) {
    $libros_reservados[] = $row;
}

echo json_encode($libros_reservados);

// Cerrar la conexión
mysqli_close($conn);
?>