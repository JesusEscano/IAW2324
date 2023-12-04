<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu foto</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER('PHP_SELF'));?>" method="post">
    <p><label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required></p>
    <p><label for="foto">Foto:</label>
    <input type="file" name="foto"></p>
    <p><input type="submit" value="Enviar datos"></p>
    </form>
<?php
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $foto = filter_var($_FILES['foto'], FILTER_SANITIZE_STRING);
    if (isset($_POST('submit'))){
    echo "<p>$nombre</p><p>$foto</p>"
}else{echo "<p></p>"};
?>
</body>
</html>