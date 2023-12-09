<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Las sesiones que no son de Bizarrap</title>
</head>
<body>
<p>INTRODUZCA SU NOMBRE Y CONTRASEÑA PARA INICIAR SESIÓN</p>
<p><em>El usuario y pass correcto es el de login.php</em></p>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="contraseña">Contraseña:</label>
        <input type="text" id="contraseña" name="contraseña" required>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php
// Envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['contraseña'])) {
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $contraseña = $_POST['contraseña'];
        // Comprobar usiario y contraseña
        $usuario_valido = 'admin';
        $contraseña_valida = password_hash('H4CK3R4$1R', PASSWORD_DEFAULT);
        if ($nombre == $usuario_valido && password_verify($contraseña, $contraseña_valida)) {
        // Guardar el nombre
        $_SESSION['nombre'] = $nombre;
        echo "<p>Acceso concedido</p>";
        } else {
            $error = "<p></p>Acceso denegado</p>";
        }
    } else {
        $error = "<p>Por favor, escriba su nombre y contraseña</p>";
    }}
if (isset($_SESSION['nombre'])) {
    echo "<p>Ya tienes la sesión inicidada</p>";
}
if (isset($error)) {
    echo "<p>$error</p>";
}
    ?>
</body>
</html>