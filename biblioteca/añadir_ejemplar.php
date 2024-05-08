<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Verificar si se envió el ID del libro
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_libro'])) {
    $id_libro = intval($_GET['id_libro']);

    // Insertar otro ejemplar del mismo libro en la base de datos
    $sql_insert_ejemplar = "INSERT INTO ejemplares (id_libro, estado) VALUES (?, 'Disponible')";
    $stmt = mysqli_prepare($conn, $sql_insert_ejemplar);
    mysqli_stmt_bind_param($stmt, "i", $id_libro);
    if (mysqli_stmt_execute($stmt)) {
        // Redirigir de vuelta a la página principal
        header("Location: verejemplares.php");
        exit(); 
    } else {
        // Si hay un error, mostrar mensaje y redirigir a la página principal
        echo "Error al añadir ejemplar";
        header("Location: verejemplares.php");
        exit(); 
    }
} else {
    // Si no se recibió el ID del libro, redirigir a la página principal
    header("Location: verejemplares.php");
    exit(); 
}

mysqli_close($conn);
?>