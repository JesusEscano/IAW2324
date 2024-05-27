<?php

include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header("Location: index.php");
    exit();
}


//Esto es FE porque Frontend y lo que no tiene _ es origen, los que tienen _ en su nombre son para cargarlo todo y luego cargar las búsquedas
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
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .imagen-libro {
            max-width: 8vw; /* Ancho máximo de 8vw, ~ 100 px */
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
        /* Estilo para los enlaces dentro de la tabla */
        table a {
        text-decoration: none; /* Quitar que se vea como enlace */
        color: inherit; /* Que se mantenga en negro, por si acaso */
        }
        /* Estilo botones y navegación */
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
            background-color: #3a5a40;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #buscar:hover {
            background-color: #5ab87a;
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

        .pagination a:hover {
            background-color: #5ab87a;
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
            <!-- Campo de búsqueda -->
            <div id="buscador">
                <input type="text" class="form-control" placeholder="Buscar por título, autor o tema." id="busqueda" aria-label="Buscar" aria-describedby="button-addon2">
                <button class="btn btn-primary" type="button" id="buscar">Buscar</button>
            </div>
            <!-- Contenido de libros, carga esto cuando entra en la página -->
            <div id="libros">
                <?php include_once 'FE_todos_libros.php'; ?>
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
                    
                }
            });
            
        });
    </script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_free_result($resultado_aviso);
mysqli_close($conn);
?>