<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

include "../header.php";

if (isset($_GET['eliminar'])) {
    $id = htmlspecialchars($_GET['eliminar']);
    
    $query = "DELETE FROM incidencias WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>window.location='home.php';</script>";
    } else {
        echo "Error al eliminar la incidencia: " . $stmt->error;
    }

    $stmt->close();
}

include "../footer.php";
?>