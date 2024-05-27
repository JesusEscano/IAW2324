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
                    <a class="nav-link" href="home.php"><i class="bi bi-house-door"></i> Vista pública</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="añadirlibro.php"><i class="bi bi-book"></i> Añadir libros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verlibros.php"><i class="bi bi-book-fill"></i> Ver libros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verejemplares.php"><i class="bi bi-journal-bookmark"></i> Ver ejemplares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verusuarios.php"><i class="bi bi-people"></i> Gestionar usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="verpeticiones.php"><i class="bi bi-ui-checks"></i> Gestionar peticiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="añadirnoticia.php"><i class="bi bi-newspaper"></i> Escribir noticia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vernoticia.php"><i class="bi bi-eye"></i> Ver noticia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editarnormas.php"><i class="bi bi-card-checklist"></i> Normas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="avisos.php"><i class="bi bi-info-square"></i> Enviar aviso</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Libros Reservados</h2>

    <!-- Tabla de libros reservados -->
    <table class="table">
        <thead>
            <tr>
                <th>Nombre del Libro</th>
                <th>ID del Ejemplar</th>
                <th>Nombre del Usuario</th>
                <th>Fecha de Reserva</th>
                <th>Fecha Máxima</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-libros-reservados">
            <!-- Aquí se mostrarán los libros reservados -->
        </tbody>
    </table>

    <h2>Libros Alquilados</h2>

    <!-- Tabla de libros alquilados -->
    <table class="table">
        <thead>
            <tr>
                <th>Nombre del Libro</th>
                <th>ID del Ejemplar</th>
                <th>Nombre del Usuario</th>
                <th>Fecha de Alquiler</th>
                <th>Fecha Máxima</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-libros-alquilados">
            <!-- Aquí se mostrarán los libros alquilados -->
        </tbody>
    </table>

    <h2>Libros Devueltos</h2>

    <!-- Tabla de libros devueltos -->
    <table class="table">
        <thead>
            <tr>
                <th>Nombre del Libro</th>
                <th>ID del Ejemplar</th>
                <th>Fecha de Devolución</th>
                <th>Estante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-libros-devueltos">
            <!-- Aquí se mostrarán los libros devueltos -->
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    // Función para cargar los libros reservados, alquilados y devueltos al cargar la página
    cargarLibrosReservados();
    cargarLibrosAlquilados();
    cargarLibrosDevueltos();

    // Función para cargar todos los libros reservados
    function cargarLibrosReservados() {
        $.ajax({
            url: "obtener_libros_reservados.php", // Ruta al script PHP que obtiene los libros reservados
            method: "GET",
            success: function(response) {
                var data = JSON.parse(response);
                var htmlReservados = '';
                
                data.forEach(function(libro) {
                    htmlReservados += '<tr>';
                    htmlReservados += '<td>' + libro.nombre_libro + '</td>';
                    htmlReservados += '<td>' + libro.id_ejemplar + '</td>';
                    htmlReservados += '<td>' + libro.nombre_usuario + '</td>';
                    htmlReservados += '<td>' + libro.fecha_reserva + '</td>';
                    htmlReservados += '<td>' + libro.fecha_maxima_reserva + '</td>';
                    htmlReservados += '<td><button class="btn btn-primary entregar-btn" data-id-ejemplar="' + libro.id_ejemplar + '">Entregar</button></td>';
                    htmlReservados += '</tr>';
                });

                $("#tabla-libros-reservados").html(htmlReservados); // Mostrar los libros reservados en la tabla
            }
        });
    }

    // Función para cargar todos los libros alquilados
    function cargarLibrosAlquilados() {
        $.ajax({
            url: "obtener_alquilados.php", // Ruta al script PHP que obtiene los libros alquilados
            method: "GET",
            success: function(response) {
                var data = JSON.parse(response);
                var htmlAlquilados = '';
                
                data.forEach(function(libro) {
                    htmlAlquilados += '<tr>';
                    htmlAlquilados += '<td>' + libro.nombre_libro + '</td>';
                    htmlAlquilados += '<td>' + libro.id_ejemplar + '</td>';
                    htmlAlquilados += '<td>' + libro.nombre_usuario + '</td>';
                    htmlAlquilados += '<td>' + libro.fecha_alquiler + '</td>';
                    htmlAlquilados += '<td>' + libro.fecha_max_alquiler + '</td>';
                    htmlAlquilados += '<td><button class="btn btn-success devolver-btn" data-id-ejemplar="' + libro.id_ejemplar + '">Devolver</button></td>';
                    htmlAlquilados += '</tr>';
                });

                $("#tabla-libros-alquilados").html(htmlAlquilados); // Mostrar los libros alquilados en la tabla
            }
        });
    }

    // Función para cargar todos los libros devueltos
    function cargarLibrosDevueltos() {
        $.ajax({
            url: "obtener_devueltos.php", // Ruta al script PHP que obtiene los libros devueltos
            method: "GET",
            success: function(response) {
                var data = JSON.parse(response);
                var htmlDevueltos = '';
                
                data.forEach(function(libro) {
                    htmlDevueltos += '<tr>';
                    htmlDevueltos += '<td>' + libro.nombre_libro + '</td>';
                    htmlDevueltos += '<td>' + libro.id_ejemplar + '</td>';
                    htmlDevueltos += '<td>' + libro.fecha_devolucion + '</td>';
                    htmlDevueltos += '<td>' + libro.estanteria + '</td>';
                    htmlDevueltos += '<td><button class="btn btn-warning ordenar-btn" data-id-ejemplar="' + libro.id_ejemplar + '">Guardado</button></td>';
                    htmlDevueltos += '</tr>';
                });

                $("#tabla-libros-devueltos").html(htmlDevueltos); // Mostrar los libros devueltos en la tabla
            }
        });
    }

    // Función para entregar un libro reservado
    $(document).on('click', '.entregar-btn', function() {
        var idEjemplar = $(this).data('id-ejemplar');
        console.log('ID del Ejemplar:', idEjemplar); // Agregamos un mensaje de consola para verificar el ID del Ejemplar
        $.ajax({
            url: "entregar_libro.php", // Ruta al script PHP que gestiona la entrega del libro
            method: "POST",
            data: {id_ejemplar: idEjemplar},
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Agregamos un mensaje de consola para verificar la respuesta del servidor
                if (response == 'success') {
                    cargarLibrosReservados(); // Recargar la lista de libros reservados
                    cargarLibrosAlquilados(); // Recargar la lista de libros alquilados
                    cargarLibrosDevueltos(); // Recargar la lista de libros devueltos
                } else {
                    alert('Error al entregar el libro');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Si hay un error, mostramos el mensaje de error en la consola
            }
        });
    });

    // Función para devolver un libro alquilado
    $(document).on('click', '.devolver-btn', function() {
        var idEjemplar = $(this).data('id-ejemplar');
        console.log('ID del Ejemplar:', idEjemplar); // Agregamos un mensaje de consola para verificar el ID del Ejemplar
        $.ajax({
            url: "devolver_libro.php", // Ruta al script PHP que gestiona la entrega del libro
            method: "POST",
            data: {id_ejemplar: idEjemplar},
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Agregamos un mensaje de consola para verificar la respuesta del servidor
                if (response == 'success') {
                    cargarLibrosReservados(); // Recargar la lista de libros reservados
                    cargarLibrosAlquilados(); // Recargar la lista de libros alquilados
                    cargarLibrosDevueltos(); // Recargar la lista de libros devueltos
                } else {
                    alert('Error al devolver el libro');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Si hay un error, mostramos el mensaje de error en la consola
            }
        });
    });

    // Función para guardar un libro devuelto
    $(document).on('click', '.ordenar-btn', function() {
        var idEjemplar = $(this).data('id-ejemplar');
        console.log('ID del Ejemplar:', idEjemplar); // Agregamos un mensaje de consola para verificar el ID del Ejemplar
        $.ajax({
            url: "guardar_libro.php", // Ruta al script PHP que gestiona la entrega del libro
            method: "POST",
            data: {id_ejemplar: idEjemplar},
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Agregamos un mensaje de consola para verificar la respuesta del servidor
                if (response == 'success') {
                    cargarLibrosReservados(); // Recargar la lista de libros reservados
                    cargarLibrosAlquilados(); // Recargar la lista de libros alquilados
                    cargarLibrosDevueltos(); // Recargar la lista de libros devueltos
                } else {
                    alert('Error al guardar el libro');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Si hay un error, mostramos el mensaje de error en la consola
            }
        });
    });
});
</script>
</body>
</html>