<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Configuración para paginación
$libros_por_pagina = 10; // Número de libros por página

// Obtener el número total de libros
$sql_total_libros = "SELECT COUNT(*) AS total FROM libros";
$resultado_total_libros = mysqli_query($conn, $sql_total_libros);
$fila_total_libros = mysqli_fetch_assoc($resultado_total_libros);
$total_libros = $fila_total_libros['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_libros / $libros_por_pagina);

// Obtener el número de página actual
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$pagina_actual = min($pagina_actual, $total_paginas); // Asegurar que la página actual no supere el número total de páginas

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $libros_por_pagina;

// Consulta para obtener los libros de la página actual
$sql_libros = "SELECT libros.id_libro, nombre_libro, 
               GROUP_CONCAT(DISTINCT nombre_autor SEPARATOR ', ') AS autores, 
               (SELECT COUNT(*) FROM ejemplares WHERE ejemplares.id_libro = libros.id_libro AND ejemplares.estado = 'Disponible') AS ejemplares_disponibles, 
               imagen_libro 
               FROM libros 
               INNER JOIN autor_libro ON libros.id_libro = autor_libro.id_libro 
               INNER JOIN autores ON autor_libro.id_autor = autores.id_autor 
               GROUP BY libros.id_libro, nombre_libro, imagen_libro 
               ORDER BY nombre_libro ASC 
               LIMIT $offset, $libros_por_pagina";

$resultado_libros = mysqli_query($conn, $sql_libros);

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
    <link rel="stylesheet" href="prueba.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .imagen-libro {
            max-width: 100px; /* Ancho máximo de 100px */
        }
        /* Centrar el contenido de las celdas de la tabla */
        table {
            border-collapse: collapse; 
            width: 100%; 
        }
        th, td {
            text-align: center; 
            padding: 5px; 
        }
        #buscador {
            margin-bottom: 20px;
            display: flex;
        }
        #busqueda {
            flex: 1;
            margin-right: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        #buscar {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #buscar:hover {
            background-color: #0056b3;
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
                <a href="reglas.html" class="nav-link" aria-label="Ir a la página de reglas">
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
            <!-- Mostrar aviso si hay uno -->
            <?php if ($hay_aviso): ?>
            <div class="aviso">
                <div class="aviso-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <p class="aviso-dice"><?php echo $texto_aviso; ?></p>
            </div>
            <?php endif; ?>
            <!-- Buscador -->
            <style>
        #buscador {
            margin-bottom: 20px;
            display: flex;
        }
        #busqueda {
            flex: 1;
            margin-right: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        #buscar {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #buscar:hover {
            background-color: #0056b3;
        }
            </style>
            <!-- Campo de búsqueda -->
            <div id="buscador">
                <input type="text" class="form-control" placeholder="Buscar por título o autor. Toca la imagen para seleccionar." id="busqueda" aria-label="Buscar" aria-describedby="button-addon2">
                <button class="btn btn-primary" type="button" id="buscar">Buscar</button>
            </div>
            <!-- Contenido de libros -->
            <div id="libros">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Ejemplares Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($resultado_libros)): ?>
                            <tr>
                                <td><?php echo $fila['imagen_libro'] ? '<img src="media/' . $fila['imagen_libro'] . '" class="imagen-libro" alt="Imagen del libro">' : ''; ?></td>
                                <td><?php echo $fila['nombre_libro']; ?></td>
                                <td><?php echo $fila['autores']; ?></td>
                                <td><?php echo $fila['ejemplares_disponibles']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
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
        </div>
    </main>
        <!-- Script para realizar la búsqueda con AJAX -->
        <script>
        $(document).ready(function(){
            // Función para realizar la búsqueda de libros
            function buscarLibros(busqueda) {
                $.ajax({
                    url: 'FE_busqueda_libros.php', // Archivo PHP para realizar la búsqueda
                    type: 'POST',
                    data: { busqueda: busqueda }, // Parámetro de búsqueda
                    success: function(response) {
                        $('#libros').html(response); // Mostrar resultados de la búsqueda
                    }
                });
            }

            // Evento al hacer clic en el botón de búsqueda
            $('#buscar').on('click', function(){
                var busqueda = $('#busqueda').val().trim(); // Obtener el valor del campo de búsqueda
                buscarLibros(busqueda); // Realizar la búsqueda
            });

            // Evento al escribir en el campo de búsqueda
            $('#busqueda').on('keyup', function(){
                var busqueda = $(this).val().trim(); // Obtener el valor del campo de búsqueda
                if(busqueda != '') {
                    buscarLibros(busqueda); // Realizar la búsqueda si el campo no está vacío
                } else {
                    $('#libros').html(''); // Limpiar los resultados si el campo está vacío
                }
            });
        });
    </script>
</body>
</html>

<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($resultado_total_libros);
mysqli_free_result($resultado_libros);
mysqli_close($conn);
?>