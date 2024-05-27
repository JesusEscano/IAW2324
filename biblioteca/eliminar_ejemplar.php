<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos, cambia si lo mueves

// Verificar si se recibió el ID del ejemplar a eliminar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_ejemplar_eliminar'])) {
    $id_ejemplar = intval($_GET['id_ejemplar_eliminar']);

    // Eliminar el ejemplar de la base de datos
    $sql_delete_ejemplar = "DELETE FROM ejemplares WHERE id_ejemplar = ?";
    $stmt = mysqli_prepare($conn, $sql_delete_ejemplar);
    mysqli_stmt_bind_param($stmt, "i", $id_ejemplar);
    if (mysqli_stmt_execute($stmt)) {
        // Redirigir de vuelta a la página principal
        header("Location: verejemplares.php");
        exit(); 
    } else {
        // Si hay un error, mostrar mensaje y redirigir a la página principal
        echo "Error al eliminar ejemplar";
        header("Location: verejemplares.php");
        exit(); 
    }
} else {
    // Si no se recibió el ID del ejemplar a eliminar, redirigir a la página principal
    header("Location: verejemplares.php");
    exit(); 
}

mysqli_close($conn);
?>