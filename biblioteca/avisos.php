<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de la biblioteca del IES Antonio Machado</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="administración.css">
    <!-- Enlace al CSS de Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
                        <a class="nav-link active" aria-current="page" href="avisos.php"><i class="bi bi-info-square"></i>
                            Enviar aviso</a>
                    </li>
                </ul>
            </div>
    </div>
</nav>


<!-- Formulario de Avisos -->
<div class="container mt-5">
    <h2>Enviar Aviso</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="contenido">Contenido:</label><br>
        <textarea id="contenido" name="contenido" style="width: 100%; height: 200px;" required></textarea><br><br>
        
        <label for="fecha_activacion">Fecha de activación:</label><br>
        <input type="date" id="fecha_activacion" name="fecha_activacion" required><br><br>
        
        <input type="submit" name="enviar_aviso" value="Enviar Aviso">
    </form>
</div>

<!-- Subir avisos a Base de Datos -->
<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $contenido = $_POST['contenido'];
    $fecha_activacion = $_POST['fecha_activacion'];

    // Validar los datos recibidos 
    if (empty($contenido) || empty($fecha_activacion)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Preparar la consulta para insertar el aviso en la tabla 'avisos'
        $sql = "INSERT INTO avisos (texto, fecha_activacion, activo) VALUES (?, ?, 1)";
        $stmt = mysqli_prepare($conn, $sql);

        // Vincular los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt, "ss", $contenido, $fecha_activacion);

        // Ejecutar la consulta preparada
        if (mysqli_stmt_execute($stmt)) {
            // Mensaje de éxito
            echo "Aviso enviado correctamente.";
        } else {
            $error_message = "Error al enviar el aviso: " . mysqli_error($conn);
        }
    }
}
?>
<!-- Estilos para la lista de Avisos -->
<style>
    /* Centrar verticalmente el contenido de las celdas */
    .table td,
    .table th {
        vertical-align: middle;
    }

    /* Asegurar que el contenido de las columnas "Activación", "Estado" y "Acciones" se muestre en una línea */
    .table td:not(:first-child),
    .table th:not(:first-child) {
        white-space: nowrap;
    }

    /* Permitir que el contenido de la columna "Aviso" se divida en múltiples líneas */
    .table td:first-child,
    .table th:first-child {
        white-space: normal;
    }
</style>
<!-- Lista de Avisos -->
<div class="container mt-5">
    <h2>Lista de Avisos</h2>
    <table class="table table-striped" style='color: black; background-color: #f5f5dc;'>
        <thead>
            <tr>
                <th>Aviso</th>
                <th>Activación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
<!-- Aquí se mostrarán los avisos desde la base de datos -->
            
            <?php
            include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

            // Consulta SQL para obtener los avisos
            $sql = "SELECT * FROM avisos";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['texto'] . "</td>";
                    echo "<td>" . $row['fecha_activacion'] . "</td>";
                    echo "<td>" . ($row['activo'] ? 'Activo' : 'Inactivo') . "</td>";
                    echo "<td>";
                    echo "<a href='editar_aviso.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Editar</a>";
                    echo "<a href='activar_aviso.php?id=" . $row['id'] . "' class='btn btn-success btn-sm'>" . ($row['activo'] ? 'Desactivar' : 'Activar') . "</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay avisos.</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>