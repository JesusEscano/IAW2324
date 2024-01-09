<?php
// Configuración de conexión a la base de datos
include_once 'secret.php';

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
//if ($conn->connect_error) {
//    die("Error de conexión a la base de datos: " . $conn->connect_error);
//}

// Consulta SQL para obtener un registro de la tabla usuarios
$sql = "INSERT INTO usuarios (username, password) VALUES ('Curro', 'YERY$%·GFHG')";
//$result = $conn->query($sql);

// Verificar si hay resultados
if ($conn->query($sql) == True) {
    // Mostrar los datos de cada fila
        echo "Nuevo registro insertado correctamente";
} else {
    echo "No se puede insertar.";
}

// Cerrar conexión
$conn->close();
?>