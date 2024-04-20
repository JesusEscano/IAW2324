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
                        <a class="nav-link" href="#"><i class="bi bi-house-door"></i>
                            Home</a>
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
                        <a class="nav-link" href="#"><i class="bi bi-people"></i>
                            Gestionar usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-ui-checks"></i>
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
                        <a class="nav-link" href="#"><i class="bi bi-info-square"></i>
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
</div>

<script>
$(document).ready(function(){
    // Función para cargar los libros al cargar la página
    cargarLibros();

    // Función para buscar libros al hacer clic en el botón "Buscar"
    $("#buscar").click(function(){
        buscarLibros();
    });

    // Función para buscar libros al presionar Enter en el campo de búsqueda
    $("#busqueda").keypress(function(event){
        if(event.which == 13) { // 13 es el código de tecla para Enter
            buscarLibros();
        }
    });

    // Función para buscar libros mientras se escribe en el campo de búsqueda
    $("#busqueda").keyup(function(){
        buscarLibros();
    });

    // Función para cargar todos los libros
    function cargarLibros() {
        $.ajax({
            url: "obtener_libros.php", // Ruta al script PHP que obtiene los libros
            method: "GET",
            success: function(data) {
                $("#tabla-libros").html(data); // Mostrar los libros en la tabla
            }
        });
    }

    // Función para buscar libros por título o autor
    function buscarLibros() {
        var busqueda = $("#busqueda").val().trim(); // Obtener el texto de búsqueda
        if(busqueda != "") {
            $.ajax({
                url: "buscar_libros.php", // Ruta al script PHP que busca los libros
                method: "POST",
                data: {busqueda: busqueda}, // Enviar el texto de búsqueda al servidor
                success: function(data) {
                    $("#tabla-libros").html(data); // Mostrar los libros encontrados en la tabla
                }
            });
        } else {
            cargarLibros(); // Si el campo de búsqueda está vacío, cargar todos los libros
        }
    }
});
</script>

</body>
</html>