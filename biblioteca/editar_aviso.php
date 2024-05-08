<?php
include_once 'bd.php'; // Archivo de conexión a la base de datos, cambiar en entrega

// Verificar si se recibió el parámetro 'id' a través de GET
if (isset($_GET['id'])) {
    $aviso_id = $_GET['id'];

    // Consulta SQL para obtener el aviso específico por su ID
    $sql = "SELECT * FROM avisos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Vincular el parámetro a la consulta preparada
    mysqli_stmt_bind_param($stmt, "i", $aviso_id);

    // Ejecutar la consulta preparada
    mysqli_stmt_execute($stmt);

    // Obtener el resultado de la consulta
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el aviso
    if ($row = mysqli_fetch_assoc($result)) {
        // Si se encuentra el aviso, mostrar el formulario para editar
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Aviso</title>
            <!-- Solo Bootstrap para dejarlo todo blanco. Si se quiere, se puede añadir el resto de CSS o enlaces de veravisos.php -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        </head>
        <body>

        <div class="container mt-5">
            <h2>Editar Aviso</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido:</label>
                    <textarea class="form-control" id="contenido" name="contenido" rows="3" required><?php echo $row['texto']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_activacion" class="form-label">Fecha de activación:</label>
                    <input type="date" class="form-control" id="fecha_activacion" name="fecha_activacion" value="<?php echo $row['fecha_activacion']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>

        </body>
        </html>

        <?php
    } else {
        // Si no se encuentra el aviso, redireccionar a la página de avisos.php
        header("Location: avisos.php");
        exit();
    }
}

// Verificar si se recibió el parámetro 'id' a través de POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $aviso_id = $_POST['id'];
    $contenido = $_POST['contenido'];
    $fecha_activacion = $_POST['fecha_activacion'];

    // Consulta SQL para actualizar el aviso específico por su ID
    $sql = "UPDATE avisos SET texto = ?, fecha_activacion = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Vincular los parámetros a la consulta preparada
    mysqli_stmt_bind_param($stmt, "ssi", $contenido, $fecha_activacion, $aviso_id);

    // Ejecutar la consulta preparada
    if (mysqli_stmt_execute($stmt)) {
        // Si la actualización es exitosa, redireccionar a la página de avisos.php
        header("Location: avisos.php");
        exit();
    } else {
        // Si hay un error en la actualización, mostrar un mensaje de error
        echo "Error al actualizar el aviso: " . mysqli_error($conn);
    }
} else {
    // Si no se recibió el parámetro 'id', redireccionar a la página de avisos.php
    
    exit();
}
?>