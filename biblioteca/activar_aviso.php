<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Verificar si se recibió el parámetro 'id' a través de GET
if (isset($_GET['id'])) {
    $aviso_id = $_GET['id'];

    // Consulta SQL para obtener el aviso específico por ID
    $sql = "SELECT * FROM avisos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Vincular el parámetro a la consulta preparada
    mysqli_stmt_bind_param($stmt, "i", $aviso_id);

    // Ejecutar la consulta preparada
    mysqli_stmt_execute($stmt);

    // Obtener el resultado de la consulta
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el aviso
    if ($row = mysqli_fetch_assoc($result)) {
        // Determinar el nuevo estado del aviso
        $nuevo_estado = $row['activo'] ? 0 : 1;

        // Consulta SQL para actualizar el estado del aviso
        $sql_update = "UPDATE avisos SET activo = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);

        // Vincular los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt_update, "ii", $nuevo_estado, $aviso_id);

        // Ejecutar la consulta preparada
        mysqli_stmt_execute($stmt_update);

        // Redireccionar a la página de avisos.php
        header("Location: avisos.php");
        exit();
    } else {
        // Redireccionar de nuevo si algo falla página de avisos.php
        header("Location: avisos.php");
        exit();
    }
} else {
    // Si no se recibió el parámetro 'id', redireccionar a la página de avisos.php
    header("Location: avisos.php");
    exit();
}
?>