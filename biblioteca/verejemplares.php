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
        #buscador {
            margin-bottom: 20px;
        }
        .imagen-libro {
            max-width: 100px;
            max-height: 150px;
        }
        .table {
            color: black;
            background-color: #f5f5dc;
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
                    <a class="nav-link" href="verlibros.php"><i class="bi bi-book-fill"></i>
                        Ver libros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="verejemplares.php"><i class="bi bi-journal-bookmark"></i>
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
    <h2>Lista de Ejemplares</h2>

    <!-- Buscador -->
    <div class="input-group mb-3" id="buscador">
        <input type="text" class="form-control" placeholder="Buscar por título, autor o estado" id="busqueda" aria-label="Buscar" aria-describedby="button-addon2">
        <button class="btn btn-primary" type="button" id="buscar">Buscar</button>
    </div>
    <!-- Tabla de ejemplares -->
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Estantería</th> <!-- Nueva columna de estantería -->
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-ejemplares">
            <!-- Aquí se mostrarán los ejemplares -->
        </tbody>
    </table>
    <div id="pagination-controls">
        <!-- Aquí se mostrarán los controles de paginación -->
    </div>
</div>

<script>
$(document).ready(function(){
    // Función para cargar los ejemplares al cargar la página
    cargarEjemplares();

    // Función para buscar ejemplares al hacer clic en el botón "Buscar"
    $("#buscar").click(function(){
        buscarEjemplares();
    });

    // Función para buscar ejemplares al presionar Enter en el campo de búsqueda
    $("#busqueda").keypress(function(event){
        if(event.which == 13) { // 13 es el código de tecla para Enter
            buscarEjemplares();
        }
    });

    // Función para buscar ejemplares mientras se escribe en el campo de búsqueda
    $("#busqueda").keyup(function(){
        buscarEjemplares();
    });

    // Función para cargar todos los ejemplares
    function cargarEjemplares(pagina = 1) {
        $.ajax({
            url: "obtener_ejemplares.php",
            method: "POST", // Cambiar a POST
            data: {pagina: pagina},
            dataType: "json",
            success: function(data) {
                $("#tabla-ejemplares").html(data.ejemplares);
                $("#pagination-controls").html(data.pagination);
            }
        });
    }

    // Función para buscar ejemplares por título o autor
    function buscarEjemplares(pagina = 1) {
    var busqueda = $("#busqueda").val().trim();
    if (busqueda !== "") {
        $.ajax({
            url: "buscar_ejemplares.php",
            method: "POST", // Cambiar a POST
            data: {busqueda: busqueda, pagina: pagina},
            dataType: "json",
            success: function(data) {
                $("#tabla-ejemplares").html(data.ejemplares);
                $("#pagination-controls").html(data.pagination);
            }
        });
    } else {
        cargarEjemplares();
    }
    }

    // Función para manejar los clics en los enlaces de paginación
    $(document).on("click", ".pagination a", function(e){
        e.preventDefault();
        var page = $(this).data("page");
        var busqueda = $("#busqueda").val().trim();
        if (busqueda !== "") {
            buscarEjemplares(page);
        } else {
            cargarEjemplares(page);
        }
    });
});
</script>

</body>
</html>