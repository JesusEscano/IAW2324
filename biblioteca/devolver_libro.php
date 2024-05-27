<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ejemplar = $_POST['id_ejemplar'];

    // Fecha de hoy
    $fecha_devolucion = date('Y-m-d');

    // Actualizar la base de datos
    $sql = "UPDATE alquiler 
            SET fecha_reserva = NULL, 
                fecha_maxima_reserva = NULL, 
                fecha_max_alquiler = NULL,
                fecha_devolucion = '$fecha_devolucion',
                situacion = 'Devuelto'
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