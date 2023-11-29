<?php
if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $fecha = date("d/m/y");
    echo "<p>Hola $nombre, hoy es $fecha</p>";
} else {
    echo "<p>No ha indicado ningún nombre</p>";
}
?>