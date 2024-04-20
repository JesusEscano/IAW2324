<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Iniciar sesión
session_start();

// Inicializar la variable $id_libro
$id_libro = null;

// Verificar si se recibió el ID del libro a editar desde la URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_libro = $_GET['id'];
    $_SESSION['id_libro'] = $id_libro; // Almacenar el ID del libro en una variable de sesión
} elseif(isset($_SESSION['id_libro']) && is_numeric($_SESSION['id_libro'])) {
    // Si no se recibió el ID del libro desde la URL, verificar si está almacenado en la sesión
    $id_libro = $_SESSION['id_libro'];
} else {
    // Si no se recibió el ID del libro ni está almacenado en la sesión, redirigir a la página de ver libros
    $_SESSION['error_message'] = "ID de libro no especificado.";
    header("Location: verlibros.php");
    exit();
}

// Obtener los datos del libro de la base de datos
$sql = "SELECT * FROM libros WHERE id_libro = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_libro);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($resultado) == 1) {
    $libro = mysqli_fetch_assoc($resultado);
} else {
    // Mostrar mensaje de error si el libro no existe
    $_SESSION['error_message'] = "El libro no existe.";
    header("Location: verlibros.php");
    exit();
}

// Verificar si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $nombre_libro = htmlspecialchars(trim($_POST['nombre_libro'])); 
    $editorial = htmlspecialchars(trim($_POST['editorial']));
    $tema = htmlspecialchars(trim($_POST['tema']));
    $sinopsis = htmlspecialchars(trim($_POST['sinopsis']));
    $paginas = intval($_POST['paginas']); // Convertir a entero
    $anio_publicacion = intval($_POST['año_publicacion']); // Convertir a entero
    $autores = isset($_POST['autores']) ? $_POST['autores'] : array(); // Array de autores
    $num_ejemplares = intval($_POST['num_ejemplares']); // Convertir a entero

    // Procesar la imagen del libro si se cargó una nueva
    if ($_FILES['imagen']['size'] > 0) {
        $ruta_imagen = "media/";
        $nombre_imagen = uniqid() . '_' . basename($_FILES['imagen']['name']); // Obtener el nombre único de la imagen
        $ruta_imagen_completa = $ruta_imagen . $nombre_imagen;

        // Guardar la imagen en la carpeta de imágenes del servidor
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen_completa)) {
            // Actualizar la ruta de la imagen en la base de datos
            $sql_update_imagen = "UPDATE libros SET imagen_libro = ? WHERE id_libro = ?";
            $stmt_update_imagen = mysqli_prepare($conn, $sql_update_imagen);
            mysqli_stmt_bind_param($stmt_update_imagen, "si", $nombre_imagen, $id_libro);
            mysqli_stmt_execute($stmt_update_imagen);
        } else {
            // Si hay un error al cargar la imagen, mostrar un mensaje de error
            $_SESSION['error_message'] = "Error al cargar la imagen.";
            header("Location: editarlibro.php?id=$id_libro");
            exit();
        }
    }

    // Actualizar el libro en la base de datos
    $sql_update_libro = "UPDATE libros SET nombre_libro = ?, editorial = ?, tema = ?, sinopsis = ?, paginas = ?, ano_publicacion = ? WHERE id_libro = ?";
    $stmt_update_libro = mysqli_prepare($conn, $sql_update_libro);
    mysqli_stmt_bind_param($stmt_update_libro, "ssssssi", $nombre_libro, $editorial, $tema, $sinopsis, $paginas, $anio_publicacion, $id_libro);
    
    // Ejecutar la consulta preparada para actualizar el libro
    if (mysqli_stmt_execute($stmt_update_libro)) {
        // Eliminar todos los autores del libro de la tabla autor_libro
        $sql_delete_autores = "DELETE FROM autor_libro WHERE id_libro = ?";
        $stmt_delete_autores = mysqli_prepare($conn, $sql_delete_autores);
        mysqli_stmt_bind_param($stmt_delete_autores, "i", $id_libro);
        mysqli_stmt_execute($stmt_delete_autores);

        // Insertar los autores actualizados en la tabla autor_libro
        foreach ($autores as $nombre_autor) {
            // Verificar si el autor ya existe en la tabla de autores
            $sql_autor_existente = "SELECT id_autor FROM autores WHERE nombre_autor = ?";
            $stmt_autor_existente = mysqli_prepare($conn, $sql_autor_existente);
            mysqli_stmt_bind_param($stmt_autor_existente, "s", $nombre_autor);
            mysqli_stmt_execute($stmt_autor_existente);
            mysqli_stmt_store_result($stmt_autor_existente);

            if (mysqli_stmt_num_rows($stmt_autor_existente) > 0) {
                // Si el autor ya existe, obtener su ID
                mysqli_stmt_bind_result($stmt_autor_existente, $id_autor);
                mysqli_stmt_fetch($stmt_autor_existente);
            } else {
                // Si el autor no existe, insertarlo en la tabla de autores y obtener su ID
                $sql_insert_autor = "INSERT INTO autores (nombre_autor) VALUES (?)";
                $stmt_insert_autor = mysqli_prepare($conn, $sql_insert_autor);
                mysqli_stmt_bind_param($stmt_insert_autor, "s", $nombre_autor);
                mysqli_stmt_execute($stmt_insert_autor);
                $id_autor = mysqli_insert_id($conn); // Obtener el ID del autor insertado
            }

            // Insertar la relación entre el autor y el libro en la tabla 'autor_libro'
            $sql_insert_autor_libro = "INSERT INTO autor_libro (id_autor, id_libro) VALUES (?, ?)";
            $stmt_insert_autor_libro = mysqli_prepare($conn, $sql_insert_autor_libro);
            mysqli_stmt_bind_param($stmt_insert_autor_libro, "ii", $id_autor, $id_libro);
            mysqli_stmt_execute($stmt_insert_autor_libro);
        }

        // Guardar mensaje de éxito en la variable de sesión
        $_SESSION['success_message'] = "Libro actualizado correctamente.";
    } else {
        // Guardar mensaje de error en la variable de sesión
        $_SESSION['error_message'] = "Error al actualizar el libro: " . mysqli_error($conn);
    }

    // Redirigir de vuelta a la página de edición después de procesar el formulario
    header("Location: editarlibro.php?id=$id_libro");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Libro</h2>

    <?php
    // Mostrar mensajes de éxito o error
    if(isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" role="alert">';
        echo $_SESSION['success_message'];
        echo '</div>';
        unset($_SESSION['success_message']);
    }

    if(isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">';
        echo $_SESSION['error_message'];
        echo '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="nombre_libro">Título:</label><br>
        <input type="text" id="nombre_libro" name="nombre_libro" style="width: 800px;" value="<?php echo $libro['nombre_libro']; ?>" required><br><br>

        <label for="editorial">Editorial:</label><br>
        <input type="text" id="editorial" name="editorial" value="<?php echo $libro['editorial']; ?>" required><br><br>

        <label for="tema">Tema:</label><br>
        <input type="text" id="tema" name="tema" value="<?php echo $libro['tema']; ?>" required><br><br>

        <label for="paginas">Páginas:</label><br>
        <input type="number" id="paginas" name="paginas" value="<?php echo $libro['paginas']; ?>" min="1" required><br><br>

        <label for="año_publicacion">Año de publicación:</label><br>
        <input type="number" id="año_publicacion" name="año_publicacion" value="<?php echo $libro['ano_publicacion']; ?>" placeholder="AAAA" pattern="[0-9]{4}" required><br><br>

        <label for="sinopsis">Sinopsis:</label><br>
        <textarea id="sinopsis" name="sinopsis" rows="4" style="width: 800px;" required><?php echo $libro['sinopsis']; ?></textarea><br><br>

        <label for="autores">Autores:</label><br>
        <div id="autores">
            <?php
            // Mostrar los autores actuales del libro
            $sql_autores = "SELECT nombre_autor FROM autores INNER JOIN autor_libro ON autores.id_autor = autor_libro.id_autor WHERE id_libro = ?";
            $stmt_autores = mysqli_prepare($conn, $sql_autores);
            mysqli_stmt_bind_param($stmt_autores, "i", $id_libro);
            mysqli_stmt_execute($stmt_autores);
            $resultado_autores = mysqli_stmt_get_result($stmt_autores);

            while($fila_autor = mysqli_fetch_assoc($resultado_autores)) {
                echo '<div class="autor">';
                echo '<input type="text" name="autores[]" style="width: 800px;" value="' . $fila_autor['nombre_autor'] . '" required>';
                echo '<button type="button" onclick="eliminarAutor(this)">Eliminar</button><br><br>';
                echo '</div>';
            }
            ?>
        </div>
        <button type="button" onclick="agregarAutor()">Agregar Autor</button><br><br>

        <label for="imagen">Imagen del Libro:</label><br>
        <input type="file" id="imagen" name="imagen"><br><br>

        <input class="btn btn-success" type="submit" value="Guardar cambios">
        <a class="btn btn-primary" href="verlibros.php">Volver a Ver Libros</a>
    </form>

</div>

</body>
</html>