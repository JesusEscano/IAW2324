<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del ejemplar del libro desde la solicitud POST
    $id_ejemplar = $_POST['id_ejemplar'];

    // Verificar si el ejemplar existe y está marcado como devuelto
    $sql_verificar = "SELECT * FROM alquiler WHERE id_ejemplar = ? AND situacion = 'Devuelto' AND fecha_devolucion IS NOT NULL";
    $stmt_verificar = mysqli_prepare($conn, $sql_verificar);
    mysqli_stmt_bind_param($stmt_verificar, 'i', $id_ejemplar);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

    if (mysqli_num_rows($resultado_verificar) > 0) {
        // El ejemplar está devuelto, proceder a marcarlo como "disponible" en ambas tablas
        $sql_actualizar_alquiler = "UPDATE alquiler SET situacion = 'Disponible' WHERE id_ejemplar = ?";
        $stmt_actualizar_alquiler = mysqli_prepare($conn, $sql_actualizar_alquiler);
        mysqli_stmt_bind_param($stmt_actualizar_alquiler, 'i', $id_ejemplar);

        $sql_actualizar_ejemplares = "UPDATE ejemplares SET estado = 'Disponible' WHERE id_ejemplar = ?";
        $stmt_actualizar_ejemplares = mysqli_prepare($conn, $sql_actualizar_ejemplares);
        mysqli_stmt_bind_param($stmt_actualizar_ejemplares, 'i', $id_ejemplar);

        if (mysqli_stmt_execute($stmt_actualizar_alquiler) && mysqli_stmt_execute($stmt_actualizar_ejemplares)) {
            echo 'success';
        } else {
            echo 'error';
        }

        // Cerrar las declaraciones
        mysqli_stmt_close($stmt_actualizar_alquiler);
        mysqli_stmt_close($stmt_actualizar_ejemplares);
    } else {
        echo 'error';
    }

    // Cerrar la declaración de verificación y la conexión
    mysqli_stmt_close($stmt_verificar);
} else {
    echo 'error';
}

mysqli_close($conn);
?>