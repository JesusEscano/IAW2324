<?php
header("refresh:3;url=https://myblog-akoycdevr3.live-website.com/php/saludo.php");
// Esto vale para que podamos incluir redirecciones con una pantalla de carga 
// para comprobar ciertos elementos antes de permitir seguir, por ejemplo, hay 
// de Cloudflare contra DDoS
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
</head>
<body>
<?php
echo "Te estamos redirigiendo a nuestra página. Por favor, espera...";
?>
</body>
</html>