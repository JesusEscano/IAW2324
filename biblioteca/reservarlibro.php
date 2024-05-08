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
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Centrar el contenido de las celdas de la tabla */
        table {
            border-collapse: collapse; 
            width: 100%; 
        }
        th, td {
            text-align: center; 
            padding: 5px; 
        }
        /* Estilos del botón */
        #reservar {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            background-color: #3a5a40;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #reservar:hover {
            background-color: #5ab87a;
        }
        /* Estilo para dejar espacio en la parte inferior */
        .container {
            margin-bottom: 80px; 
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
    <!-- La barra de navegación lateral (o inferior si la pantalla es pequeña), cambia los enlaces si los mueves -->
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
                <a href="normas.php" class="nav-link" aria-label="Ir a la página de reglas">
                    <img src="media/reglas.png" alt="Ícono de reglas">
                    <span class="link-text">Reglas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="FE_buscarlibro.php" class="nav-link-active" aria-current="page" aria-label="Buscar libro">
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
            <?php
            include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar por el bueno

            // Verificar si se proporcionó un ID de libro
            $id_libro = isset($_GET['id']) ? $_GET['id'] : null;

            if ($id_libro !== null) {
                // Consulta para obtener los detalles del libro específico
                $sql_libro = "SELECT libros.id_libro, nombre_libro, 
                GROUP_CONCAT(DISTINCT nombre_autor SEPARATOR ', ') AS autores, 
                editorial, tema, paginas, ano_publicacion, sinopsis, imagen_libro 
                FROM libros 
                INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
                INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
                WHERE libros.id_libro = $id_libro";

                $resultado_libro = mysqli_query($conn, $sql_libro);

                // Verificar si se encontró el libro
                if(mysqli_num_rows($resultado_libro) > 0) {
                    $fila = mysqli_fetch_assoc($resultado_libro);
                    echo '<div style="text-align: center;">';
                    echo '<img src="media/' . $fila['imagen_libro'] . '" alt="Imagen del libro" style="max-height: 45vh;">';
                    echo '</div>';
                    echo '<div style="margin: 0 auto; text-align: center; max-width: 600px;">'; // Contenedor centrado
                    echo '<button id="reservar">Reservar</button>';
                    echo '<p><strong>Título:</strong> <span>' . $fila['nombre_libro'] . '</span></p>';
                    echo '<p><strong>Autor(es):</strong> ' . $fila['autores'] . '</p>';
                    echo '<p><strong>Editorial:</strong> ' . $fila['editorial'] . '</p>';
                    echo '<p><strong>Género:</strong> ' . $fila['tema'] . '</p>';
                    echo '<p><strong>Número de páginas:</strong> ' . $fila['paginas'] . '</p>';
                    echo '<p><strong>Año de publicación:</strong> ' . $fila['ano_publicacion'] . '</p>';
                    echo '<p><strong>Sinopsis:</strong></p>';
                    echo '<p>' . $fila['sinopsis'] . '</p>';
                    echo '</div>';
                } else {
                    echo '<p>No se encontró el libro con el ID especificado</p>';
                }

                // Liberar resultados
                mysqli_free_result($resultado_libro);
            } else {
                echo '<p>No se especificó ningún ID de libro</p>';
            }

            // Cerrar la conexión
            mysqli_close($conn);
            ?>
        </div>
    </main>

    <!-- Script para cambiar el color del botón al hacer hover -->
    <script>
        $(document).ready(function(){
            $('#reservar').on('mouseover', function() {
                $(this).css('background-color', '#5ab87a');
            }).on('mouseout', function() {
                $(this).css('background-color', '#3a5a40');
            });

        });
    </script>
</body>
</html>