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
                        <a class="nav-link active" aria-current="page" href="añadirnoticia.php"><i class="bi bi-newspaper"></i>
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

<!-- Formulario de Noticias -->
<div class="container mt-5">
    <h2>Escribir Noticia</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" style="width: 800px;" required><br><br>
        
        <label for="fecha_publicacion">Fecha de publicación:</label><br>
        <input type="date" id="fecha_publicacion" name="fecha_publicacion" required><br><br>
        
        <label for="contenido">Contenido:</label><br>
        <div id="editor-container" style="height: 400px;"></div>
        <textarea id="contenido" name="contenido" style="display:none;"></textarea><br><br>
        
        <label for="imagenes">Imágenes:</label><br>
        <input type="file" id="imagenes" name="imagenes[]" multiple><br><br>
        
        <input type="submit" value="Enviar">
    </form>
</div>

<!-- Enlace al JS de Quill -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

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

<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $titulo = $_POST['titulo'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $contenido = $_POST['contenido'];

    // Validar los datos recibidos 
    if (empty($titulo) || empty($fecha_publicacion) || empty($contenido)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Preparar la consulta para insertar la noticia en la tabla 'noticias'
        $sql = "INSERT INTO noticias (titulo, fecha_pub, contenido) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Vincular los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt, "sss", $titulo, $fecha_publicacion, $contenido);

        // Ejecutar la consulta preparada
        if (mysqli_stmt_execute($stmt)) {
            // Obtener el ID de la noticia recién insertada
            $id_noticia = mysqli_insert_id($conn);

            // Recorrer y guardar las imágenes asociadas a la noticia
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                $nombre_archivo = $_FILES['imagenes']['name'][$key];
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nombre_imagen = "imagen" . $id_noticia . "." . $extension;
                $ruta_imagen = "media/" . $nombre_imagen; // Ruta donde se guardará la imagen en el servidor

                // Mover la imagen al directorio de imágenes en el servidor
                move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $ruta_imagen);

                // Preparar la consulta para actualizar la noticia con la ruta de la imagen
                $sql_update = "UPDATE noticias SET imagen_noticia = ? WHERE id_noticia = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);

                // Vincular los parámetros a la consulta preparada
                mysqli_stmt_bind_param($stmt_update, "si", $nombre_imagen, $id_noticia);
                mysqli_stmt_execute($stmt_update);
            }
            // Mensaje de éxito
            echo "Noticia insertada correctamente en la base de datos.";
            // Redireccionar a la página de inicio u otra página
            // header("Location: borrarnoticia.php");
            exit();
        } else {
            $error_message = "Error al añadir la noticia: " . mysqli_error($conn);
        }
    }
}
?>
</body>
</html>