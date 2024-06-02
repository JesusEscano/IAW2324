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
    <h2>Histórico de Alquileres <a href="verpeticiones.php" class="btn btn-info ms-3"><i class="bi bi-arrow-left"></i> Regresar</a></h2>

    <!-- Buscador -->
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="busqueda" placeholder="Buscar por usuario o libro">
        <button class="btn btn-primary" id="buscar-btn">Buscar</button>
    </div>

    <!-- Tabla de histórico de alquileres -->
    <table class="table table-dark">
        <thead>
            <tr>
                <th>Nombre del Libro</th>
                <th>ID del Ejemplar</th>
                <th>Usuario</th>
                <th>Fecha de Alquiler</th>
                <th>Fecha de Devolución</th>
            </tr>
        </thead>
        <tbody id="tabla-historico-alquileres">
            <!-- Aquí se mostrarán los datos del histórico de alquileres -->
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    // Función para cargar el histórico de alquileres
    function cargarHistoricoAlquileres(busqueda = '') {
        $.ajax({
            url: "obtener_historico_alquileres.php",
            method: "GET",
            data: { busqueda: busqueda },
            success: function(response) {
                var data = JSON.parse(response);
                var htmlHistorico = '';
                
                data.forEach(function(alquiler) {
                    htmlHistorico += '<tr>';
                    htmlHistorico += '<td>' + alquiler.nombre_libro + '</td>';
                    htmlHistorico += '<td>' + alquiler.id_ejemplar + '</td>';
                    htmlHistorico += '<td>' + alquiler.correo + '</td>';
                    htmlHistorico += '<td>' + alquiler.fecha_alquiler + '</td>';
                    htmlHistorico += '<td>' + alquiler.fecha_devolucion + '</td>';
                    htmlHistorico += '</tr>';
                });

                $("#tabla-historico-alquileres").html(htmlHistorico); // Mostrar el histórico de alquileres en la tabla
            }
        });
    }

    // Cargar el histórico de alquileres al cargar la página
    cargarHistoricoAlquileres();

    // Evento de keyup para el campo de búsqueda
    $('#busqueda').on('keyup', function() {
        var busqueda = $(this).val();
        cargarHistoricoAlquileres(busqueda);
    });

    // Evento de click para el botón de búsqueda
    $('#buscar-btn').click(function() {
        var busqueda = $('#busqueda').val();
        cargarHistoricoAlquileres(busqueda);
    });
});
</script>
</body>
</html>