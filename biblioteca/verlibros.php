<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de la biblioteca del IES Antonio Machado</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="administración.css">
    <style>
        /* Estilo opcional para el buscador */
        #buscador {
            margin-bottom: 20px;
        }
        .imagen-libro {
            max-width: 100px;
            max-height: 150px;
        }
        .table {
            color: white;
        }
        /* Estilo opcional para la paginación */
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
            border: 1px solid #000;
            border-radius: 3px;
            color: #fff;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #000;
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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Biblioteca IES Antonio Machado</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php"><i class="bi bi-house-door"></i>
                        Vista pública</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="añadirlibro.php"><i class="bi bi-book"></i>
                        Añadir libros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="verlibros.php"><i class="bi bi-book-fill"></i>
                        Ver libros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verejemplares.php"><i class="bi bi-journal-bookmark"></i>
                        Ver ejemplares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verusuarios.php"><i class="bi bi-people"></i>
                        Gestionar usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verpeticiones.php"><i class="bi bi-ui-checks"></i>
                        Gestionar peticiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="añadirnoticia.php"><i class="bi bi-newspaper"></i>
                        Escribir noticia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vernoticia.php"><i class="bi bi-eye"></i>
                        Ver noticia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editarnormas.php"><i class="bi bi-card-checklist"></i>
                        Normas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="avisos.php"><i class="bi bi-info-square"></i>
                        Enviar aviso</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Lista de Libros</h2>

    <!-- Buscador -->
    <div class="input-group mb-3" id="buscador">
        <input type="text" class="form-control" placeholder="Buscar por título o autor" id="busqueda" aria-label="Buscar" aria-describedby="button-addon2">
        <button class="btn btn-primary" type="button" id="buscar">Buscar</button>
    </div>

    <!-- Tabla de libros -->
    <table class="table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Año de Publicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-libros">
            <!-- Aquí se mostrarán los libros -->
        </tbody>
    </table>
    <div id="pagination-controls">
        <!-- Aquí se mostrarán los controles de paginación -->
    </div>
</div>

<script>
$(document).ready(function(){
    // Función para cargar los libros al cargar la página
    cargarLibros(1);

    // Función para buscar libros al hacer clic en el botón "Buscar"
    $("#buscar").click(function(){
        buscarLibros(1);
    });

    // Función para buscar libros al presionar Enter en el campo de búsqueda
    $("#busqueda").keypress(function(event){
        if(event.which == 13) { // 13 es el código de tecla para Enter
            buscarLibros(1);
        }
    });

    // Función para buscar libros mientras se escribe en el campo de búsqueda
    $("#busqueda").keyup(function(){
        buscarLibros(1);
    });

    // Función para manejar la paginación
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        var page = $(this).attr('data-page');
        cargarLibros(page);
    });

    // Función para cargar todos los libros
    function cargarLibros(pagina) {
        $.ajax({
            url: "obtener_libros.php", // Ruta al script PHP que obtiene los libros
            method: "GET",
            data: {pagina: pagina},
            success: function(response) {
                var data = JSON.parse(response);
                $("#tabla-libros").html(data.libros); // Mostrar los libros en la tabla
                $("#pagination-controls").html(data.pagination); // Mostrar los controles de paginación
            }
        });
    }

    // Función para buscar libros por título o autor
    function buscarLibros(pagina) {
        var busqueda = $("#busqueda").val().trim(); // Obtener el texto de búsqueda
        if(busqueda != "") {
            $.ajax({
                url: "buscar_libros.php", // Ruta al script PHP que busca los libros
                method: "POST",
                data: {busqueda: busqueda, pagina: pagina},
                success: function(response) {
                    var data = JSON.parse(response);
                    $("#tabla-libros").html(data.libros); // Mostrar los libros encontrados en la tabla
                    $("#pagination-controls").html(data.pagination); // Mostrar los controles de paginación
                }
            });
        } else {
            cargarLibros(pagina); // Si el campo de búsqueda está vacío, cargar todos los libros
        }
    }
});
</script>

</body>
</html>