<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo 'Debes iniciar sesión para reservar un libro';
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_libro = isset($_POST['id_libro']) ? $_POST['id_libro'] : null;

if ($id_libro === null) {
    echo 'No se proporcionó ningún ID de libro';
    exit();
}

// Verificar el número total de reservas y alquileres del usuario
$sql_total_reservas = "SELECT COUNT(*) AS total 
                       FROM alquiler 
                       WHERE id_usuario = $id_usuario AND (situacion = 'Reservado' OR situacion = 'Alquilado')";
$resultado_total_reservas = mysqli_query($conn, $sql_total_reservas);
$fila_total_reservas = mysqli_fetch_assoc($resultado_total_reservas);

if ($fila_total_reservas['total'] >= 5) {
    echo 'No puedes reservar más de 5 libros';
    exit();
}

// Verificar si el usuario ya tiene el mismo libro reservado o alquilado
$sql_mismo_libro = "SELECT COUNT(*) AS total 
                    FROM alquiler 
                    INNER JOIN ejemplares ON alquiler.id_ejemplar = ejemplares.id_ejemplar 
                    WHERE alquiler.id_usuario = $id_usuario 
                    AND ejemplares.id_libro = $id_libro 
                    AND (alquiler.situacion = 'Reservado' OR alquiler.situacion = 'Alquilado')";
$resultado_mismo_libro = mysqli_query($conn, $sql_mismo_libro);
$fila_mismo_libro = mysqli_fetch_assoc($resultado_mismo_libro);

if ($fila_mismo_libro['total'] > 0) {
    echo 'Ya tienes este libro reservado o alquilado';
    exit();
}

// Verificar si hay ejemplares disponibles del libro
$sql_ejemplar = "SELECT id_ejemplar FROM ejemplares WHERE id_libro = $id_libro AND estado = 'Disponible' LIMIT 1";
$resultado_ejemplar = mysqli_query($conn, $sql_ejemplar);

if (mysqli_num_rows($resultado_ejemplar) > 0) {
    $ejemplar = mysqli_fetch_assoc($resultado_ejemplar);
    $id_ejemplar = $ejemplar['id_ejemplar'];

    // Obtener la fecha actual y calcular la fecha máxima de reserva
    $fecha_reserva = date('Y-m-d');
    $fecha_max_reserva = date('Y-m-d', strtotime($fecha_reserva . ' + 3 days'));

    // Registrar la reserva en la tabla alquiler
    $sql_reserva = "INSERT INTO alquiler (id_ejemplar, situacion, id_usuario, fecha_reserva, fecha_maxima_reserva) 
                    VALUES ($id_ejemplar, 'Reservado', $id_usuario, '$fecha_reserva', '$fecha_max_reserva')";
    if (mysqli_query($conn, $sql_reserva)) {
        // Actualizar el estado del ejemplar a 'Reservado'
        $sql_update_ejemplar = "UPDATE ejemplares SET estado = 'Alquilado' WHERE id_ejemplar = $id_ejemplar";
        mysqli_query($conn, $sql_update_ejemplar);
        echo 'Reserva realizada con éxito';
    } else {
        echo 'Error al realizar la reserva: ' . mysqli_error($conn);
    }
} else {
    echo 'No quedan copias disponibles del libro';
}

// Cerrar la conexión
mysqli_close($conn);
?>