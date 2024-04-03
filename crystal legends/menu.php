<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CL menu</title>
    <link href="https://fonts.cdnfonts.com/css/luminari" rel="stylesheet">
    <link rel="stylesheet" href="menu.css">
</head>
<body>
    <div class="wrapper">
        <div class="tienda"><img src="imagenes/tienda.png" alt="tienda">
            <div class="nombre_tienda">Tienda</div>
        </div>
        <div class="cristal"><img src="imagenes/cristalazul.png" alt="azul"><?php echo $cristales_a; ?><img src="imagenes/cristalrojo.png" alt="rojo"><?php echo $cristales_r; ?></div>
        <div class="user">Usuario</div>
        <div class="logout">Desloguear</div>
        <div class="batalla1">Batalla 1</div>
        <div class="batalla2">Batalla 2</div>
        <div class="stats">Estadísticas</div>
        <div class="option">Opciones</div>
        </div>
    </div>
    <?php
include_once 'bd.php'; // Incluye el archivo de conexión a la base de datos

// Realiza la consulta para obtener los valores de cristales_a y cristales_r
$query = "SELECT cristales_a, cristales_r FROM usuarios";
$result = mysqli_query($conexion, $query);

if ($result) {
    // Obtiene los valores de cristales_a y cristales_r
    $row = mysqli_fetch_assoc($result);
    $cristales_a = $row['cristales_a'];
    $cristales_r = $row['cristales_r'];
}
?>
</body>
</html>