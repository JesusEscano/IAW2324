<?php

include_once 'bd.php'; // Archivo de conexión a la base de datos, seguramente en otro lado en la versión definitiva

// Configuración para paginación
$noticias_por_pagina = 2; // Número de noticias por página

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

// Calcular el offset para la consulta SQL (que no se note que lo miré en internet)
$offset = ($pagina_actual - 1) * $noticias_por_pagina;

// Consulta para obtener las noticias de la página actual
$sql_noticias = "SELECT * FROM noticias ORDER BY fecha_pub DESC LIMIT $noticias_por_pagina OFFSET $offset";
$resultado_noticias = mysqli_query($conn, $sql_noticias);

// Consulta para obtener el aviso activo
$sql_aviso = "SELECT * FROM avisos WHERE activo = 1 ORDER BY fecha_activacion DESC LIMIT 1";
$resultado_aviso = mysqli_query($conn, $sql_aviso);

// Verificar si hay algún aviso activo
if (mysqli_num_rows($resultado_aviso) > 0) {
    $fila_aviso = mysqli_fetch_assoc($resultado_aviso);
    $hay_aviso = true;
    $texto_aviso = $fila_aviso['texto'];
} else {
    $hay_aviso = false;
    $texto_aviso = "";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca IES Antonio Machado</title>
    <!-- CSS -->
    <link rel="stylesheet" href="home.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- El nombre de la biblioteca, es fijo arriba -->
    <nav class="navbar-top">
        <ul class="navbar-top-nav">
            <li class="nav-item">
                <h1>Biblioteca del IES Antonio Machado</h1>
            </li>
        </ul>
    </nav>
    <!-- La barra de navegación lateral (o inferior si la pantalla es pequeña) -->
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="logo">
                <a href="#" class="nav-link" aria-label="Ir a la página de inicio">
                    <img src="media/machadologocontorno.png" alt="Logotipo del IES Antonio Machado">
                </a>
            </li>
            <li class="nav-item">
                <a href="home.php" class="nav-link-active" aria-current="page" aria-label="Ir a la página de inicio">
                    <img src="media/home.png" alt="Ícono de casa">
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="normas.php" class="nav-link" aria-label="Ir a la página de reglas">
                    <img src="media/reglas.png" alt="Ícono de reglas">
                    <span class="link-text">Reglas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="FE_buscarlibro.php" class="nav-link" aria-label="Buscar libro">
                    <img src="media/librolupa.png" alt="Ícono de lupa">
                    <span class="link-text">Buscar libro</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" aria-label="Ir a libros pedidos">
                    <img src="media/libroi.png" alt="Ícono de libros pedidos">
                    <span class="link-text">Libros pedidos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" aria-label="Ir a perfil">
                    <img src="media/user.png" alt="Ícono de perfil">
                    <span class="link-text">Perfil</span>
                </a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="container">
        <!-- Mostrar aviso si hay uno -->
        <style>
        /* Esto está aquí porque Tinkerhost hay veces que da porculo para cargar los CSS y en línea se los come */
        /* Estilos para la tarjeta de aviso */
        .aviso {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff3cd;
            border: 2px solid #721c24;
            border-radius: .25rem;
        }
        /* Estilos para el icono de aviso */
        .aviso-icon {
            font-size: 24px;
            text-align: center;
            color: #721c24;
        }
        /* Estilos para el texto del aviso */
        .aviso-dice {
            font-size: 18px;
            text-align: center;
            margin-top: 10px;
        }
        /* Valor base del margen inferior, que la barra se pone abajo y puede tapar cosas */
        .container {
            margin-bottom: 80px;
        }

        /* Reiteración o modificaciones del CSS cuando no cargaba el del enlace y ya no recuerdo si son redundantes o no, así que se quedan */
        .card-content p {
        margin-bottom: 0;
        font-size: 18px;
        text-align: left;
        }
        
        .card-content li {
        margin-bottom: 0;
        font-size: 18px;
        text-align: left;
        }

        /* Adaptación del ancho de las noticias adaptado al menú lateral */
        .noticia {
        display: flex;
        margin-top: 20px;
        width: calc(98vw - 6.3rem);
        }

        /* Esto es para el menú de paginación, lo de los numeritos para pasar a otra página */
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
        border: 1px solid #3a5a40;
        border-radius: 3px;
        color: #3a5a40;
        text-decoration: none;
        }

        .pagination a.active {
        background-color: #3a5a40;
        color: #fff;
        }

        .pagination a.anterior, .pagination a.siguiente {
        width: auto; /* Ancho automático para "Anterior" y "Siguiente" */
        padding-left: 10px;
        padding-right: 10px;
        }

        .noticia-content {
        flex: 3;
        padding-right: 20px; /* Ajuste del espaciado entre la imagen y el texto */
}       
        </style>
            <!-- Primero del todo mostrar el aviso -->
            <?php if ($hay_aviso): ?>
            <div class="aviso">
            <div class="aviso-icon">
            <i class="bi bi-info-circle"></i>
            </div>
            <p class="aviso-dice"><?php echo $texto_aviso; ?></p>
            </div>
            <?php endif; ?>
            <!-- Contenido de noticias -->
            <?php while ($fila = mysqli_fetch_assoc($resultado_noticias)): ?>
                <div class="card">
                    <div class="card-content">
                        <h2 style="text-align: center;"><?php echo $fila['titulo']; ?></h2>
                        <p class="fecha-publicacion" style="text-align: center;">Publicado el <?php echo date('d/m/Y', strtotime($fila['fecha_pub'])); ?></p>
                        <?php if ($fila['imagen_noticia']): ?>
                            <div class="noticia-image" style="text-align: center;">
                                <img src="media/<?php echo $fila['imagen_noticia']; ?>" alt="Imagen de la noticia" style="max-width: 100%; height: auto;">
                            </div>
                        <?php endif; ?>
                        <p style='text-align: left;'><?php echo $fila['contenido']; ?></p>
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
            <!-- Controles de paginación -->
            <style>

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