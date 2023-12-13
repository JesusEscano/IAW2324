<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva PHP</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <p><label for="nombre">Nombre: </label><input type="text" id="nombre" name="nombre"></p>
    <p><label for="apellidos">Apellidos: </label><input type="text" id="apellidos" name="apellidos"></p>
    <p><label for="mail">Email: </label><input type="email" id="mail" name="mail"></p>
    <p><label for="dni">DNI: </label><input type="text" id="dni" name="dni"></p>
    <p><label for="entrada">Día de entrada: </label><input type="date" id="entrada" name="entrada"></p>
    <p><label for="dias">Días que se va a quedar: </label><input type="number" id="dias" name="dias"></p>
    <p><label for="habitacion">Habitación: <select name="habitacion" class="form-control" id="habitacion">
        <option value="simple">Simple(30€)</option>
        <option value="doble">Doble(50€)</option>
        <option value="triple">Triple(80€)</option>
        <option value="suite">Suite(100€)</option>
      </select></p>
    <p><input type="submit" name="submit" value="Solicitar habitación"></p>
    </form>
    <?php
if (isset($_POST['submit'])) {
  if (isset($_POST['nombre']) && !empty($_POST['nombre']) 
    && isset($_POST['apellidos']) && !empty($_POST['apellidos'])
    && isset($_POST['mail']) && !empty($_POST['mail'])
    && isset($_POST['dni']) && !empty($_POST['dni'])
    && isset($_POST['entrada']) && !empty($_POST['entrada'])
    && isset($_POST['dias']) && !empty($_POST['dias'])
    && isset($_POST['habitacion']) && !empty($_POST['habitacion'])) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $apellidos = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL); //Hay un sanitize para mails
    $dni = filter_var($_POST['dni'], FILTER_SANITIZE_STRING);
    $entrada = filter_var($_POST['entrada'], FILTER_SANITIZE_STRING);
    $dias = filter_var($_POST['dias'], FILTER_SANITIZE_STRING);
    $habitacion = filter_var($_POST['habitacion'], FILTER_SANITIZE_STRING);
    $hab=0;
    $coste=0;
    if ($habitacion=='simple'){$hab = 'hab0.png'; $coste=30*$dias;} 
    elseif ($habitacion=='doble'){$hab = 'hab1.png'; $coste=50*$dias; }
    elseif ($habitacion=='triple'){$hab = 'hab2.png'; $coste=80*$dias; }
    else {$hab = 'hab3.png'; $coste=100*$dias; }
echo "<p>$nombre $apellidos, DNI: $dni</p>";
echo "<p>Correo Electrónico: $mail</p>";
echo "<p>Día de entrada: $entrada, se quedará $dias días</p>";
echo "<p>El montante total será de $coste €</p>";
echo "<p><img src='$hab' alt='habitación escogida'></p>";
} else {
    echo "<p>Necesitamos que rellene todos los datos</p>";
}}
else {echo "<p></p>";}
?>
</body>
</html>