<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro.php</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <p><label for="nombre">Usuario:</label>
        <input type="text" id="nombre" name="nombre" required></p>
        <p><label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required></p>
        <p><label for="nombrec">Nombre completo:</label>
        <input type="text" id="nombrec" name="nombrec" required></p>
        <p><label for="mail">Email:</label>
        <input type="email" id="mail" name="mail" required></p>
        <p><input type="submit" name="submit" value="Enviar"></p>
    </form>
    <?php
include_once 'secret.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $nombrec = filter_var($_POST['nombrec'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $mail, $nombre);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: El usuario ya existe.";
    } else {
        $sql = "INSERT INTO usuarios (username, password, fullname, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $contraseña, $nombrec, $mail);
        
        if ($stmt->execute()) {
            echo "Usuario registrado con éxito.";

            $to = $mail;
            $subject = "Registro Exitoso";
            $mensaje = "Gracias por registrarte.";
            $headers = "From: jesusescano83@iesamachado.org";

            mail($to, $subject, $mensaje, $headers);
        } else {
            echo "Error al registrar el usuario.";
        }
    }
    $conn->close();
}
?>
</body>
</html>