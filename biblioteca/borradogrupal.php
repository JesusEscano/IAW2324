<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuarios por Correo Electrónico</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- jQuery (para Ajax) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="administración.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Biblioteca IES Antonio Machado</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- La navbar que se mueve al lateral o abajo, cambia los enlaces si los mueves -->
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
    <h1 class="text-center">Eliminar Usuarios por Correo Electrónico</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="file" class="form-label">Selecciona un archivo TXT con correos electrónicos:</label>
        <input type="file" name="file" id="file" class="form-control" accept=".txt" required>
        <button type="submit" name="delete_users" class="btn btn-danger mt-3">Eliminar Usuarios</button>
    </form>
</div>

<div class="container mt-3">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];

        if (is_uploaded_file($file)) {
            // Leer el contenido del archivo
            $contenido = file_get_contents($file);

            if ($contenido !== false) {
                // Dividir la cadena en correos individuales
                $correos = array_map('trim', explode(',', $contenido));
                $correos = array_filter($correos); // Eliminar posibles elementos vacíos

                // Verificación de correos electrónicos
                if (count($correos) > 0) {
                    // Escapar cada correo y ejecutar la consulta para eliminar usuarios con los correos especificados
                    include_once 'bd.php'; // Incluir archivo de conexión a la base de datos

                    foreach ($correos as $correo) {
                        $correo_escapado = mysqli_real_escape_string($conn, $correo);
                        $sql = "DELETE FROM usuarios WHERE correo = '$correo_escapado'";
                        if (mysqli_query($conn, $sql)) {
                            echo '<div class="alert alert-success" role="alert">Usuario con correo ' . htmlspecialchars($correo) . ' eliminado correctamente.</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error al eliminar usuario con correo ' . htmlspecialchars($correo) . ': ' . mysqli_error($conn) . '</div>';
                        }
                    }

                    // Cerrar la conexión
                    mysqli_close($conn);
                } else {
                    echo '<div class="alert alert-warning" role="alert">No se encontraron correos electrónicos válidos en el archivo.</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Error al leer el archivo.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al subir el archivo.</div>';
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Por favor, sube un archivo de texto.</div>';
    }
    ?>
</div>

</body>
</html>