<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fecha y hora</title>
</head>
<body>
    <h1>Fecha y hora actual</h1>
<?php
setlocale(LC_TIME, 'es_ES.UTF-8');
$hechayhora = strftime('%A, %d de %B de %Y %H:%M:%S');
echo "<p>$hechayhora</p>";
?>
</body>
</html>