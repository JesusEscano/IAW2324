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
    <!-- Enlace al JS de Quill -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
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
                        <a class="nav-link active" aria-current="page" href="editarnormas.php"><i class="bi bi-card-checklist"></i>
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

<!-- Contenedor del editor Quill -->
<div class="container mt-5">
    <h2>Reglas que se muestran</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <!-- Campo oculto para almacenar el contenido HTML del editor -->
        <input type="hidden" id="contenido" name="contenido">
        <!-- Contenedor del editor Quill -->
        <div id="editor-container" style="height: 300px;">
            <?php
            include_once 'bd.php'; // Archivo de conexión a la base de datos

            // Obtener el contenido de la columna "reglas" desde la base de datos
            $sql = "SELECT reglas FROM reglas";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo $row['reglas'];
            }
            ?>
        </div>
        <br>
        <!-- Botón para guardar -->
        <button type="submit" class="btn btn-primary">Guardar Reglas</button>
    </form>
    <!-- Mensaje de éxito o error -->
    <div id="mensaje" class="mt-3 text-center">
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
        } elseif (isset($success_message)) {
            echo "<div class='alert alert-success' role='alert'>$success_message</div>";
        }
        ?>
    </div>
</div>

<!-- PHP para guardar el contenido en la base de datos -->
<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el contenido HTML del editor
    $contenido = $_POST['contenido'];

    // Validar el contenido recibido 
    if (empty($contenido)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Preparar la consulta para actualizar el contenido en la tabla 'reglas'
        $sql = "UPDATE reglas SET reglas = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Vincular el contenido al parámetro de la consulta preparada
        mysqli_stmt_bind_param($stmt, "s", $contenido);

        // Ejecutar la consulta preparada
            if (mysqli_stmt_execute($stmt)) {
        // Mensaje de éxito
            echo "Reglas actualizadas correctamente.";
        } else {
            $error_message = "Error al actualizar las reglas: " . mysqli_error($conn);
        }
    }
}
?>

<!-- Script JavaScript para la inicialización del editor Quill -->
<script>
    // Inicialización del editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],  
                ['link', 'image', 'video']
            ]
        }
    });

    // Obtener el contenido HTML del editor y asignarlo al campo oculto
    $('form').on('submit', function() {
        var contenidoHTML = quill.root.innerHTML;
        $('#contenido').val(contenidoHTML);
    });
</script>

</body>
</html>