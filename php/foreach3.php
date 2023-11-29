<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uso de for each parte 3</title>
</head>
<body>
<?php
$palabras = array("¿Jugamos al tutti frutti?", "Oh, sho soy buenísimo", "¿Cómo es?", 
"Se dice una palabra y...", "No, se elige al azar. Lo estás explicando para la mierda");
foreach($palabras as $val) {
  echo "$val<br>";}
    ?>
</body>
</html>