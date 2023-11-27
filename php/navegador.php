<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegador</title>
</head>
<body>
    <h1>Navegador</h1>
<?php
$navi = $_SERVER['HTTP_USER_AGENT'];
echo '<p>Estás navegando con $navi</p>'?>;
</body>
</html>