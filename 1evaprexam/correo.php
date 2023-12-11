<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo PHP</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
    <p><label for="asunto">Asunto: </label><input type="text" id="asunto" name="asunto"></p>
    <p><label for="destinatario">Destinatario: </label><input type="email" id="mail" name="mail"></p>
    <p><label for="mensaje">Mensaje: </label><textarea id="msg" name="msg" rows="15" cols="50"></textarea></p>
    <p><input type="submit" name="submit" value="Enviar correo"></p>
    </form>
    <?php
if (isset($_GET['submit'])) {
    $asunto = filter_var($_GET['asunto'], FILTER_SANITIZE_STRING);
    $destinatario = filter_var($_GET['mail'], FILTER_SANITIZE_EMAIL); //Hay un sanitize para mails
    $mensaje = filter_var($_GET['msg'], FILTER_SANITIZE_STRING);
if (empty($destinatario)) {
        echo "<p>Por favor, indique un correo válido</p>";
        } else {
mail($destinatario, $asunto, $mensaje);
echo "<p>Correo enviado</p>";
        }
    } else {
        echo "<p></p>";
    }
    //He tardado 20 minutos
?>
</body>
</html>