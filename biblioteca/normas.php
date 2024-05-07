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
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <style>
        .container {
            margin-bottom: 80px; /* Valor base del margen inferior */
        }
    </style>
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
                <a href="home.php" class="nav-link" aria-label="Ir a la página de inicio">
                    <img src="media/home.png" alt="Ícono de casa">
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="normas.php" class="nav-link-active" aria-current="page" aria-label="Ir a la página de reglas">
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
            <!-- Contenido de reglas -->
            <?php
            // Establecer la conexión a la base de datos
            include_once 'bd.php';

            // Consulta SQL para obtener el contenido de la columna "reglas"
            $sql = "SELECT reglas FROM reglas";
            $resultado = mysqli_query($conn, $sql);

            // Comprobar si se encontraron resultados
            if (mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                echo "<div class='reglas'>";
                echo "<p>" . $fila['reglas'] . "</p>";
                echo "</div>";
            } else {
                echo "<p>No se encontraron reglas.</p>";
            }

            // Cerrar la conexión
            mysqli_close($conn);
            ?>
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