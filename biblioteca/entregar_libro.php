<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ejemplar = $_POST['id_ejemplar'];

    // Fecha de hoy
    $fecha_alquiler = date('Y-m-d');

    // Fecha máxima de alquiler (15 días después de hoy)
    $fecha_maxima_alquiler = date('Y-m-d', strtotime($fecha_alquiler . ' + 15 days'));

    // Ajustar si cae en sábado, domingo o día festivo
    $festivos = ['2024-01-01', '2024-12-25']; // Añadir fechas de festivos nacionales
    while (date('N', strtotime($fecha_maxima_alquiler)) >= 6 || in_array($fecha_maxima_alquiler, $festivos)) {
        $fecha_maxima_alquiler = date('Y-m-d', strtotime($fecha_maxima_alquiler . ' + 1 day'));
    }

    // Actualizar la base de datos
    $sql = "UPDATE alquiler 
            SET fecha_reserva = NULL, 
                fecha_maxima_reserva = NULL, 
                fecha_alquiler = '$fecha_alquiler', 
                fecha_max_alquiler = '$fecha_maxima_alquiler',
                situacion = 'Alquilado'
            WHERE id_ejemplar = $id_ejemplar";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($conn); // Mostrar error de MySQL en caso de fallo
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>