<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitación PHP</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <p><label for="nombre">Nombre: </label><input type="text" id="nombre" name="nombre"></p>
    <p><label for="apellidos">Apellidos: </label><input type="text" id="apellidos" name="apellidos"></p>
    <p><label for="mail">Email: </label><input type="email" id="mail" name="mail"></p>
    <p><label for="dni">DNI: </label><input type="text" id="dni" name="dni"></p>
    <p><label for="habitacion">Habitación: <select name="habitacion" class="form-control" id="habitacion">
        <option value="simple">Simple(65€)</option>
        <option value="doble">Doble(80€)</option>
        <option value="triple">Triple(140€)</option>
        <option value="suite">Suite(180€)</option>
      </select></p>
    <p><input type="submit" name="submit" value="Solicitar habitación"></p>
    </form>
    <?php
if (isset($_POST['submit'])) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $apellidos = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL); //Hay un sanitize para mails
    $dni = filter_var($_POST['dni'], FILTER_SANITIZE_STRING);
    $habitacion = filter_var($_POST['habitacion'], FILTER_SANITIZE_STRING);
    $hab=0;
    if ($habitacion=='simple'){$hab = 'examimg/hab0.png';} 
    elseif ($habitacion=='doble'){$hab = 'examimg/hab1.png'; }
    elseif ($habitacion=='triple'){$hab = 'examimg/hab2.png'; }
    else {$hab = 'examimg/hab3.png'; }
echo "<p>$nombre $apellidos, DNI: $dni</p>";
echo "<p>Correo Electrónico: $mail</p>";
echo "<p><img src='$hab' alt='habitación escogida'></p>";
}
else {echo "<p></p>";}
    //Me llevó 30 minutos
?>
</body>
</html>