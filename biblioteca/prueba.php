<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre_libro = $_POST['nombre_libro']; 
    $editorial = $_POST['editorial'];
    $tema = $_POST['tema'];
    $sinopsis = $_POST['sinopsis'];
    $paginas = $_POST['paginas'];
    $anio_publicacion = $_POST['año_publicacion'];
    $autores = $_POST['autores']; // Array de autores
    $num_ejemplares = $_POST['num_ejemplares'];

    // Imprimir el título para verificar si se captura correctamente
    echo "Título del libro recibido: " . $nombre_libro . "<br>";

    // Verificar si el nombre del libro está llegando correctamente
    if ($nombre_libro) {
        echo "El nombre del libro se recibió correctamente.<br>";
    } else {
        echo "Error: no se recibió el nombre del libro.<br>";
    }

    // Ruta donde se guardará la imagen en el servidor
    $ruta_imagen = "media/";
    $nombre_imagen = uniqid() . '_' . $_FILES['imagen_libro']['name'];
    $ruta_imagen_completa = $ruta_imagen . $nombre_imagen;

    // Validar si se subió correctamente la imagen
    if (move_uploaded_file($_FILES['imagen_libro']['tmp_name'], $ruta_imagen_completa)) {
        // Preparar la consulta para insertar el libro en la tabla 'libros'
        $sql_insert_libro = "INSERT INTO libros (paginas, editorial, tema, ano_publicacion, sinopsis, imagen_libro, nombre_libro) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $stmt_insert_libro = mysqli_prepare($conn, $sql_insert_libro);

        // Vincular los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt_insert_libro, "sssssss", $paginas, $editorial, $tema, $anio_publicacion, $sinopsis, $nombre_imagen, $nombre_libro);

        // Ejecutar la consulta preparada
        if (mysqli_stmt_execute($stmt_insert_libro)) {
            // Obtener el ID del libro insertado
            $id_libro = mysqli_insert_id($conn);

            // Insertar los autores en la tabla de autores y la relación en la tabla autor_libro
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

            // Insertar ejemplares
            for ($i = 0; $i < $num_ejemplares; $i++) {
                $sql_insert_ejemplar = "INSERT INTO ejemplares (id_libro) VALUES (?)";
                $stmt_insert_ejemplar = mysqli_prepare($conn, $sql_insert_ejemplar);
                mysqli_stmt_bind_param($stmt_insert_ejemplar, "i", $id_libro);
                mysqli_stmt_execute($stmt_insert_ejemplar);
            }

            // Consulta para recuperar el nombre del libro insertado
            $sql_select_nombre_libro = "SELECT nombre_libro FROM libros WHERE id_libro = ?";
            $stmt_select_nombre_libro = mysqli_prepare($conn, $sql_select_nombre_libro);
            mysqli_stmt_bind_param($stmt_select_nombre_libro, "i", $id_libro);
            mysqli_stmt_execute($stmt_select_nombre_libro);
            mysqli_stmt_bind_result($stmt_select_nombre_libro, $nombre_libro_insertado);
            mysqli_stmt_fetch($stmt_select_nombre_libro);

            // Mensaje de éxito con el nombre del libro insertado
            echo "Libro insertado correctamente en la base de datos con el nombre: " . $nombre_libro_insertado . "<br>";
            // Redireccionar a la página de inicio u otra página
            // header("Location: inicio.php");
            exit();
        } else {
            $error_message = "Error al añadir el libro: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Error al subir la imagen.";
    }
    
    // Segundo echo de comprobación
    echo "Después de la ejecución del código.";
}
?>
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
                        <a class="nav-link active" aria-current="page" href="añadirlibro.php"><i class="bi bi-book"></i>
                            Añadir libros</a>
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

<!-- Formulario de libros -->
<div class="container mt-5">
    <h2>Añadir Libro</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="nombre_libro">Título:</label><br>
        <input type="text" id="nombre_libro" name="nombre_libro" required><br><br>

        <label for="editorial">Editorial:</label><br>
        <input type="text" id="editorial" name="editorial" required><br><br>

        <label for="tema">Tema:</label><br>
        <input type="text" id="tema" name="tema" required><br><br>

        <label for="paginas">Páginas:</label><br>
        <input type="number" id="paginas" name="paginas" min="1" required><br><br>

        <label for="año_publicacion">Año de publicación:</label><br>
        <input type="number" id="año_publicacion" name="año_publicacion" placeholder="AAAA" pattern="[0-9]{4}" required><br><br>

        <label for="sinopsis">Sinopsis:</label><br>
        <textarea id="sinopsis" name="sinopsis" rows="4" style="width: 800px;" required></textarea><br><br>

        <label for="autores">Autores:</label><br>
        <div id="autores">
            <div class="autor">
                <input type="text" name="autores[]" style="width: 800px;" required>
                <button type="button" onclick="eliminarAutor(this)">Eliminar</button><br><br>
            </div>
        </div>
        <button type="button" onclick="agregarAutor()">Agregar Autor</button><br><br>

        <label for="imagen_libro">Imagen del Libro:</label><br>
        <input type="file" id="imagen_libro" name="imagen_libro" accept="image/*" required><br><br>

        <label for="num_ejemplares">Número de Ejemplares:</label><br>
        <input type="number" id="num_ejemplares" name="num_ejemplares" min="1" required><br><br>

        <input type="submit" value="Añadir Libro">
    </form>
</div>

<script>
    function agregarAutor() {
        var divAutores = document.getElementById("autores");
        var nuevoAutor = document.createElement("div");
        nuevoAutor.className = "autor";
        nuevoAutor.innerHTML = '<input type="text" name="autores[]" style="width: 800px;" required><button type="button" onclick="eliminarAutor(this)">Eliminar</button><br><br>';
        divAutores.appendChild(nuevoAutor);
    }

    function eliminarAutor(elemento) {
        elemento.parentNode.remove();
    }
</script>

</body>
</html>