<?php
// Configuración de conexión a la base de datos
include_once 'secret.php';

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta SQL para añadir nuevas columnas
$sql = "ALTER TABLE usuarios ADD COLUMN fullname VARCHAR(255), ADD COLUMN email VARCHAR(255)";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Mostrar mensaje si se añadieron las columnas exitosamente
    echo "Añadidas nuevas columnas.";
} else {
    // Mostrar mensaje de error si no se pudieron añadir las columnas
    echo "Error al añadir columnas: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>