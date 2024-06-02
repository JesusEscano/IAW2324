<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Verificar si se recibió el parámetro de búsqueda
$busqueda = isset($_GET['busqueda']) ? mysqli_real_escape_string($conn, $_GET['busqueda']) : '';

// Consulta para obtener el histórico de alquileres, incluyendo la búsqueda por nombre del libro o del usuario
$sql_historico = "SELECT 
                    libros.nombre_libro, 
                    ejemplares.id_ejemplar, 
                    usuarios.nombre_usuario, 
                    alquiler.fecha_alquiler, 
                    alquiler.fecha_devolucion
                  FROM alquiler
                  INNER JOIN ejemplares ON alquiler.id_ejemplar = ejemplares.id_ejemplar
                  INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
                  INNER JOIN usuarios ON alquiler.id_usuario = usuarios.id_usuario
                  WHERE (libros.nombre_libro LIKE '%$busqueda%' 
                  OR usuarios.nombre_usuario LIKE '%$busqueda%')
                  ORDER BY alquiler.fecha_devolucion DESC";

$resultado_historico = mysqli_query($conn, $sql_historico);

// Verificar si la consulta fue exitosa
if (!$resultado_historico) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

// Obtener los datos del histórico de alquileres
$historico_alquileres = [];
while ($row = mysqli_fetch_assoc($resultado_historico)) {
    $historico_alquileres[] = $row;
}

echo json_encode($historico_alquileres);

// Cerrar la conexión
mysqli_close($conn);
?>