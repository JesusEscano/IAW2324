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
<?php include_once 'bd.php'; // Incluye el archivo de conexión a la base de datos ?>
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
                    <a class="nav-link" href="añadirlibro.php"><i class="bi bi-book"></i>
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
                    <a class="nav-link active" aria-current="page" href="vernoticia.php"><i class="bi bi-eye"></i>
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
<style>.card-body {
    color: black; /* Cambiar el color del texto a negro */
}

.card-title {
    color: red; /* Cambiar el color del título de la noticia a rojo */
}

.card-text {
    color: black; /* Cambiar el color del contenido de la noticia a negro */
}</style>
<!-- Lista de Noticias -->
<div class="container mt-5">
    <h2>Lista de Noticias</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php
        // Consulta para obtener todas las noticias
        $sql = "SELECT * FROM noticias";
        $result = mysqli_query($conn, $sql);
        // Verificar si hay noticias disponibles
        if (mysqli_num_rows($result) > 0) {
            // Iterar sobre cada noticia
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['titulo']; ?></h5>
                            <p class="card-text"><?php echo $row['contenido']; ?></p>
                            <?php if ($row['imagen_noticia']): ?>
                                <img src="media/<?php echo $row['imagen_noticia']; ?>" class="img-fluid" alt="Imagen de la noticia">
                            <?php endif; ?>
                            <!-- Botones para editar y borrar noticia -->
<div class="btn-group" role="group">
    <!-- Editar noticia -->
    <a href="editarnoticia.php?id_noticia=<?php echo $row['id_noticia']; ?>" class="btn btn-primary" style="margin-right: 5px; margin-top: 5px">Editar</a>
    <!-- Borrar noticia -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id_noticia" value="<?php echo $row['id_noticia']; ?>">
        <button type="submit" class="btn btn-danger" style="margin-right: 5px; margin-top: 5px" onclick="return confirm('Va a borrar la noticia de id <?php echo $row['id_noticia']; ?>, ¿está seguro?')">Borrar</button>
    </form>
</div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No hay noticias disponibles.";
        }
        ?>
    </div>
</div>

<?php
// Procesar el formulario de borrado cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se envió el ID de la noticia a borrar
    if (isset($_POST["id_noticia"])) {
        $id_noticia = mysqli_real_escape_string($conn, $_POST["id_noticia"]);

        // Eliminar la noticia de la base de datos
        $sql_delete = "DELETE FROM noticias WHERE id_noticia = $id_noticia";
        if (mysqli_query($conn, $sql_delete)) {
            // Recargar la página
            echo '<script>window.location.reload()</script>';
            exit(); // Asegúrate de terminar el script después de la recarga de la página
        } else {
            echo '<script>alert("Error al borrar la noticia: ' . mysqli_error($conn) . '");</script>';
        }
    }
}
?>

</body>
</html>