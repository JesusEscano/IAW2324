<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login.php</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="contraseña">Contraseña:</label>
        <input type="text" id="contraseña" name="contraseña" required>
        <input type="submit" name="submit" value="Enviar">
    </form>
<?php
include_once 'config.php';
if (isset($_POST['submit'])) {
if (isset($_POST['nombre']) && !empty($_POST['nombre']) 
&& isset($_POST['contraseña']) && !empty($_POST['contraseña'])) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $contraseña = filter_var($_POST['contraseña'], FILTER_SANITIZE_STRING);
    if ($nombre==$userval && $contraseña==$conval) {
        echo "<p>Acceso concedido " . $userval . "</p>";    
    }
    else {echo "Acceso denegado";}
} else {
    echo "<p>Por favor, escriba su nombre y contraseña</p>";
}}
?>
</body>
</html>