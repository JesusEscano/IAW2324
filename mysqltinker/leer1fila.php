<?php
// Configuración de conexión a la base de datos
include_once 'secret.php';

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta SQL para obtener un registro de la tabla usuarios
$sql = "SELECT * FROM usuarios LIMIT 1";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Mostrar los datos de la primera fila
    $row = $result->fetch_assoc();
    echo "ID: " . $row["id"]. " - Nombre: " . $row["username"]. " - Contraseña: " . $row["password"]. "<br>";
} else {
    echo "No se encontraron registros en la tabla usuarios.";
}

// Cerrar conexión
$conn->close();
?>