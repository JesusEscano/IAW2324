<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

$correo = $_GET['correo'];

$sql = "SELECT * FROM usuarios WHERE correo LIKE '%$correo%'";
$resultado = mysqli_query($conn, $sql);

while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    echo "<td>{$fila['id_usuario']}</td>";
    echo "<td>{$fila['correo']}</td>";
    echo "<td><button class='btn btn-primary seleccionar-usuario' data-id='{$fila['id_usuario']}' data-correo='{$fila['correo']}'>Seleccionar</button></td>";
    echo "</tr>";
}

mysqli_close($conn);
?>