<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Aquí tienes que meter la comprobación de que el usuario es admin

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquiler Manual - Biblioteca IES Antonio Machado</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Alquiler Manual <a href="verpeticiones.php" class="btn btn-danger ms-3"><i class="bi bi-backspace-fill"></i> Volver</a></h2>
    
    <!-- Buscador de usuarios por correo -->
    <div class="mb-3">
        <input type="text" id="busqueda-usuario" class="form-control" placeholder="Buscar usuario por correo">
    </div>
    <table class="table" id="tabla-usuarios">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <!-- Resultados de usuarios -->
        </tbody>
    </table>

    <!-- Mostrar información del usuario seleccionado -->
    <div id="usuario-seleccionado" style="display: none;">
        <h3>Usuario Seleccionado</h3>
        <p><strong>ID:</strong> <span id="usuario-id"></span></p>
        <p><strong>Correo:</strong> <span id="usuario-correo"></span></p>
    </div>

    <!-- Buscador de libros -->
    <div class="mt-4">
        <input type="text" id="busqueda-libro" class="form-control" placeholder="Buscar libro por título">
    </div>
    <table class="table mt-3" id="tabla-libros">
        <thead>
            <tr>
                <th>ID Libro</th>
                <th>Título</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <!-- Resultados de libros -->
        </tbody>
    </table>

    <!-- Mostrar información del libro seleccionado -->
    <div id="libro-seleccionado" style="display: none;">
        <h3>Libro Seleccionado</h3>
        <p><strong>ID:</strong> <span id="libro-id"></span></p>
        <p><strong>Título:</strong> <span id="libro-titulo"></span></p>
    </div>

    <!-- Botón para confirmar alquiler -->
    <div class="mt-4">
        <button class="btn btn-primary" id="confirmar-alquiler" style="display: none;">Confirmar Alquiler</button>
    </div>
</div>

<script>
$(document).ready(function() {
    // Buscar usuario por correo
    $('#busqueda-usuario').on('keyup', function() {
        var correo = $(this).val();
        if (correo.length > 2) {
            $.ajax({
                url: 'buscar_usuario.php',
                type: 'GET',
                data: { correo: correo },
                success: function(response) {
                    $('#tabla-usuarios tbody').html(response);
                }
            });
        } else {
            $('#tabla-usuarios tbody').html('');
        }
    });

    // Seleccionar usuario
    $(document).on('click', '.seleccionar-usuario', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var correo = $(this).data('correo');
        
        $('#usuario-id').text(id);
        $('#usuario-nombre').text(nombre);
        $('#usuario-correo').text(correo);
        $('#usuario-seleccionado').show();
        
        $('#confirmar-alquiler').show();
    });

    // Buscar libro por título
    $('#busqueda-libro').on('keyup', function() {
        var busqueda = $(this).val();
        if (busqueda.length > 2) {
            $.ajax({
                url: 'buscar_libro.php',
                type: 'GET',
                data: { busqueda: busqueda },
                success: function(response) {
                    $('#tabla-libros tbody').html(response);
                }
            });
        } else {
            $('#tabla-libros tbody').html('');
        }
    });

    // Seleccionar libro
    $(document).on('click', '.seleccionar-libro', function() {
        var id = $(this).data('id');
        var titulo = $(this).data('nombre_libro');
        
        $('#libro-id').text(id);
        $('#libro-titulo').text(titulo);
        $('#libro-seleccionado').show();
    });

    // Confirmar alquiler
    $('#confirmar-alquiler').on('click', function() {
        var id_usuario = $('#usuario-id').text();
        var id_libro = $('#libro-id').text();
        
        $.ajax({
            url: 'confirmar_alquiler.php',
            type: 'POST',
            data: { id_usuario: id_usuario, id_libro: id_libro },
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });
});
</script>
</body>
</html>