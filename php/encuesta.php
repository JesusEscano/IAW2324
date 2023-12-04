<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER('PHP_SELF'));?>" method="post">
    <p><label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required></p>
        <p><label for="preg1">¿Dónde escuchas música?</label>
    <select name="opciones1" id="preg1">
        <option value="1">Spotify</option>
        <option value="2">YouTube</option>
        <option value="3">MP3</option>
      </select></p>
    <p>Indica tus intereses:
<input type="checkbox" name="bolos"> Bolos
<input type="checkbox" name="deportes"> Deportes
<input type="checkbox" name="videojuegos"> Videojuegos
    </p>
    <p>¿Qué usas más?
<input type="radio" name="uso" value="tv">Televisión
<input type="radio" name="uso" value="pc">Ordenador
<input type="radio" name="uso" value="movil">Móvil
    </p>
    <p><input type="submit" value="Enviar datos"></p>
    </form>
<?php
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $opciones = filter_var($_POST['taskOption'], FILTER_SANITIZE_STRING);
    $checkbox = filter_var($_POST['checkbox'], FILTER_SANITIZE_STRING);
    $radio = filter_var($_POST['radio'], FILTER_SANITIZE_STRING);
    if (isset($_POST('Submit'))){
    echo "<p>$nombre, escuchas música en $opciones, te gusta $checkbox, usas $radio</p>";
}else{echo "<p></p>"};
?>
</body>
</html>