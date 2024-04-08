<?php
include_once 'bd.php'; // Incluye el archivo de conexión a la base de datos

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id_noticia = $_POST['id_noticia'];
    $titulo = $_POST['titulo'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $contenido = $_POST['contenido'];

    // Validar los datos recibidos (puedes agregar más validaciones aquí)
    if (empty($titulo) || empty($fecha_publicacion) || empty($contenido)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Verificar si se cargaron imágenes
        if (!empty($_FILES['imagenes']['name'][0])) {
            // Directorio donde se guardarán las imágenes
            $directorio_imagenes = 'media/';

            // Recorrer cada imagen cargada
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                $nombre_archivo = $_FILES['imagenes']['name'][$key];
                $ruta_imagen = $directorio_imagenes . basename($nombre_archivo);

                // Mover la imagen al directorio de imágenes
                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $ruta_imagen)) {
                    // Actualizar la noticia para incluir la ruta de la imagen
                    $nombre_imagen = basename($nombre_archivo);
                    $sql_update_imagen = "UPDATE noticias SET imagen_noticia = ? WHERE id_noticia = ?";
                    $stmt_update_imagen = mysqli_prepare($conn, $sql_update_imagen);
                    mysqli_stmt_bind_param($stmt_update_imagen, "si", $nombre_imagen, $id_noticia);
                    mysqli_stmt_execute($stmt_update_imagen);
                } else {
                    $error_message = "Error al subir la imagen.";
                    // Puedes manejar el error de carga de imágenes aquí
                }
            }
        }

        // Preparar la consulta para actualizar la noticia en la tabla 'noticias'
        $sql_update = "UPDATE noticias SET titulo = ?, fecha_pub = ?, contenido = ? WHERE id_noticia = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);

        // Vincular los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt_update, "sssi", $titulo, $fecha_publicacion, $contenido, $id_noticia);

        // Ejecutar la consulta preparada
        if (mysqli_stmt_execute($stmt_update)) {
            // Redireccionar a la página para ver las noticias
            header("Location: vernoticia.php");
            exit();
        } else {
            $error_message = "Error al actualizar la noticia: " . mysqli_error($conn);
        }
    }
}

// Verificar si se envió el ID de la noticia a editar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_noticia"])) {
    $id_noticia = $_GET["id_noticia"];

    // Preparar la consulta para obtener la noticia a editar
    $sql = "SELECT * FROM noticias WHERE id_noticia = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Vincular el parámetro a la consulta preparada
    mysqli_stmt_bind_param($stmt, "i", $id_noticia);

    // Ejecutar la consulta preparada
    mysqli_stmt_execute($stmt);

    // Obtener el resultado de la consulta
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró la noticia
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "No se encontró la noticia para editar.";
        exit();
    }
} else {
    echo "No se ha seleccionado ninguna noticia para editar.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Enlace al CSS de Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Noticia</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_noticia" value="<?php echo $row['id_noticia']; ?>">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" style="width: 800px;" value="<?php echo $row['titulo']; ?>" required><br><br>
        
        <label for="fecha_publicacion">Fecha de publicación:</label><br>
        <input type="date" id="fecha_publicacion" name="fecha_publicacion" value="<?php echo $row['fecha_pub']; ?>" required><br><br>
        
        <label for="contenido">Contenido:</label><br>
        <div id="editor-container" style="height: 400px;">
            <?php echo $row['contenido']; ?>
        </div>
        <!-- Campo oculto para el contenido del editor -->
        <input type="hidden" id="contenido" name="contenido" value=""><br><br>
        
        <label for="imagenes">Imágenes:</label><br>
        <?php if (!empty($row['imagen_noticia'])): ?>
            <p><?php echo $row['imagen_noticia']; ?></p>
        <?php else: ?>
            <p>No hay imagen asociada.</p>
        <?php endif; ?>
        <input type="file" id="imagenes" name="imagenes[]" accept="image/*"><br><br>
        
        <input type="submit" value="Actualizar">
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

    // Obtener el contenido HTML del editor y asignarlo al campo oculto antes de enviar el formulario
    document.querySelector('form').onsubmit = function() {
        var contenidoHTML = quill.root.innerHTML;
        document.querySelector('#contenido').value = contenidoHTML;
    };
</script>

<?php
// Liberar resultados y cerrar la conexión
mysqli_close($conn);
?>
</body>
</html>