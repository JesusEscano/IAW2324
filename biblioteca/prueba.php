<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar por el bueno

// Configuración para paginación
$noticias_por_pagina = 2; // Número de noticias por página, pon más si quieres

// Obtener el número total de noticias
$sql_total_noticias = "SELECT COUNT(*) AS total FROM noticias";
$resultado_total_noticias = mysqli_query($conn, $sql_total_noticias);
$fila_total_noticias = mysqli_fetch_assoc($resultado_total_noticias);
$total_noticias = $fila_total_noticias['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_noticias / $noticias_por_pagina);

// Obtener el número de página actual
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$pagina_actual = min($pagina_actual, $total_paginas); // Asegurar que la página actual no supere el número total de páginas

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $noticias_por_pagina;

// Consulta para obtener las noticias de la página actual
$sql_noticias = "SELECT * FROM noticias ORDER BY fecha_pub DESC LIMIT $noticias_por_pagina OFFSET $offset";
$resultado_noticias = mysqli_query($conn, $sql_noticias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca IES Antonio Machado</title>
    <!-- CSS -->
    <link rel="stylesheet" href="prueba.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar-top">
        <ul class="navbar-top-nav">
            <li class="nav-item">
                <h1>Biblioteca del IES Antonio Machado</h1>
            </li>
        </ul>
    </nav>
    <!-- Barra de navegación, probablemente moverás todos los enlaces a otros lugares, así que revisa la lista -->
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="logo">
                <a href="#" class="nav-link">
                    <img src="media/machadologocontorno.png" alt="logomachado">
                </a>
            </li>
            <li class="nav-item">
                <a href="home.html" class="nav-link-active" aria-current="page">
                    <img src="media/home.png" alt="casa">
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="reglas.html" class="nav-link">
                    <img src="media/reglas.png" alt="reglas">
                    <span class="link-text">Reglas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="media/librolupa.png" alt="buscar">
                    <span class="link-text">Buscar libro</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="media/libroi.png" alt="libros pedidos">
                    <span class="link-text">Libros pedidos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="media/user.png" alt="perfil">
                    <span class="link-text">Perfil</span>
                </a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="container">
            <!-- Contenido de noticias -->
            <?php while ($fila = mysqli_fetch_assoc($resultado_noticias)): ?>
                <div class="card">
                    <div class="card-content">
                        <h2 style="text-align: center;"><?php echo $fila['titulo']; ?></h2>
                        <p class="fecha-publicacion" style="text-align: center;">Publicado el <?php echo $fila['fecha_pub']; ?></p>
                        <?php if ($fila['imagen_noticia']): ?>
                            <div class="noticia-image" style="text-align: center;">
                                <img src="media/<?php echo $fila['imagen_noticia']; ?>" alt="Imagen de la noticia" style="max-width: 100%; height: auto;">
                            </div>
                        <?php endif; ?>
                        <p><?php echo $fila['contenido']; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
                <!-- Controles de paginación -->
            <div class="pagination">
                <?php if ($total_paginas > 1): ?>
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="anterior">Anterior</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a <?php echo ($i == $pagina_actual) ? 'class="active"' : ''; ?> href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="siguiente">Siguiente</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <!-- CSS perrillero que he colado aquí porque me cansaba de esperar a Tinkerhost -->
            <style>
.card-content p {
    margin-bottom: 0;
    font-size: 18px;
    text-align: left;
}

.noticia {
    display: flex;
    margin-top: 20px;
    width: calc(98vw - 6.3rem);
}

.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    margin-right: 5px;
    border: 1px solid #007bff;
    border-radius: 3px;
    color: #007bff;
    text-decoration: none;
}

.pagination a.active {
    background-color: #007bff;
    color: #fff;
}

.pagination a.anterior, .pagination a.siguiente {
    width: auto; /* Ancho automático para "Anterior" y "Siguiente" */
    padding-left: 10px;
    padding-right: 10px;
}

.noticia-content {
    flex: 3;
    padding-right: 20px; /* Ajusta el espaciado entre la imagen y el texto */
}
            </style>
        </div>
    </main>
</body>
</html>

<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($resultado_total_noticias);
mysqli_free_result($resultado_noticias);
mysqli_close($conn);
?>