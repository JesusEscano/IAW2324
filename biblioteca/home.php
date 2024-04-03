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
    <!-- Estilos adicionales -->
    <style>
        .noticia-image {
            text-align: center;
        }
        .noticia-image img {
            max-width: 100%;
            height: auto;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            background-color: #f2f2f2;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
<?php include_once 'bd.php'; // Incluye el archivo de conexión a la base de datos ?>
    <nav class="navbar-top">
        <ul class="navbar-top-nav">
            <li class="nav-item">
                <h1>Biblioteca del IES Antonio Machado</h1>
            </li>
        </ul>
    </nav>
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="logo">
                <a href="#" class="nav-link">
                    <img src="media/machadologocontorno.png" alt="logomachado">
                </a>
            </li>
        </div>
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
            <div class="card">
              <div class="card-image">
                <img src="media/warning.png" alt="Warning">
              </div>
              <div class="card-content">
                <h2>ATENCIÓN</h2>
                <p>Has sido sancionado por incumplir alguna de las normas de la biblioteca. No podrás reservar un nuevo libro hasta el X/Y/Z.</p>
              </div>
            </div>
            <div class="noticia">
                <div class="noticia-content">
                    <?php
                    // Definir el número de noticias por página
                    $noticias_por_pagina = 2;

                    // Calcular el total de noticias y páginas
                    $sql_total = "SELECT COUNT(*) AS total FROM noticias";
                    $result_total = mysqli_query($conn, $sql_total);
                    $row_total = mysqli_fetch_assoc($result_total);
                    $total_noticias = $row_total['total'];
                    $total_paginas = ceil($total_noticias / $noticias_por_pagina);

                    // Obtener el número de página actual
                    $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

                    // Calcular el desplazamiento
                    $offset = ($pagina_actual - 1) * $noticias_por_pagina;

                    // Consulta SQL con LIMIT y OFFSET para la paginación
                    $sql = "SELECT n.*, i.ruta_imagen FROM noticias n LEFT JOIN imagenes_noticias i ON n.id_noticia = i.id_noticia ORDER BY n.fecha_pub DESC LIMIT $noticias_por_pagina OFFSET $offset";
                    $result = mysqli_query($conn, $sql);

                    // Verificar si hay noticias
                    if (mysqli_num_rows($result) > 0) {
                        // Iterar sobre cada noticia y mostrarla
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="noticia">';
                            echo '<div class="noticia-content">';
                            echo '<h2>' . $row['titulo'] . '</h2>';
                            echo '<p class="fecha-publicacion">Publicado el ' . date('d-m-Y', strtotime($row['fecha_pub'])) . '</p>';
                            // Mostrar la imagen asociada si hay una
                            if (!empty($row['ruta_imagen'])) {
                                echo '<div class="noticia-image">';
                                echo '<img src="' . $row['ruta_imagen'] . '" alt="Imagen de la noticia">';
                                echo '</div>';
                            }
                            echo '<p>' . $row['contenido'] . '</p>';
                            echo '<p>Autor: ' . $row['autor'] . '</p>'; // Ajusta esto según el nombre del campo en tu tabla de noticias
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hay noticias publicadas.</p>';
                    }

                    // Mostrar controles de paginación
                    echo '<div class="pagination">';
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo '<a href="?pagina=' . $i . '" class="' . ($pagina_actual == $i ? 'active' : '') . '">' . $i . '</a>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
          </div>
    </main>
</body>
</html>