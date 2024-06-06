<?php
include_once 'bd.php'; // Incluir el archivo de conexión a la base de datos, cambia si es necesario

// Verificar si se recibió el parámetro id_ejemplar en la URL
if(isset($_GET['id_ejemplar'])) {
    // Obtener el id del ejemplar de la URL
    $id_ejemplar = $_GET['id_ejemplar'];

    // Consulta SQL para obtener los datos del ejemplar
    $sql = "SELECT ejemplares.id_ejemplar, ejemplares.estanteria, ejemplares.estado, libros.nombre_libro
            FROM ejemplares
            INNER JOIN libros ON ejemplares.id_libro = libros.id_libro
            WHERE ejemplares.id_ejemplar = ?";
    
    // Preparar la consulta SQL
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_ejemplar);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el ejemplar
    if(mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_libro = $fila['nombre_libro'];
        $estanteria = $fila['estanteria'];
        $estado = $fila['estado'];
    } else {
        // Si no se encontró el ejemplar, redirigir o mostrar un mensaje de error
        header("Location: verejemplares.php"); // Redirigir a la página de visualización de ejemplares
        exit();
    }
} else {
    // Si no se recibió el parámetro id_ejemplar en la URL, redirigir o mostrar un mensaje de error
    header("Location: verejemplares.php"); // Redirigir a la página de visualización de ejemplares
    exit();
}

// Verificar si se envió el formulario para actualizar los datos del ejemplar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y sanitizar las entradas
    $nueva_estanteria = htmlspecialchars($_POST['nueva_estanteria']);
    $nuevo_estado = htmlspecialchars($_POST['nuevo_estado']);

    // Consulta SQL para actualizar los datos del ejemplar
    $sql_update = "UPDATE ejemplares SET estanteria = ?, estado = ? WHERE id_ejemplar = ?";
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, "ssi", $nueva_estanteria, $nuevo_estado, $id_ejemplar);
    
    // Ejecutar la consulta SQL
    if(mysqli_stmt_execute($stmt)) {
        // Mostrar un mensaje de éxito y redirigir después de 3 segundos
        echo '<script>alert("Los cambios se han aplicado correctamente."); setTimeout(function(){ window.location.href = "verejemplares.php"; }, 1000);</script>';
        exit();
    } else {
        // Mostrar un mensaje de error si la consulta falla
        $error_message = "Error al actualizar los datos del ejemplar.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ejemplar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Ejemplar</h2>
        <?php if(isset($error_message)) echo '<div class="alert alert-danger">'.$error_message.'</div>'; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id_ejemplar=".$id_ejemplar; ?>" method="POST">
            <div class="mb-3">
                <label for="nombre_libro" class="form-label">Título del Libro:</label>
                <input type="text" class="form-control" id="nombre_libro" name="nombre_libro" value="<?php echo $nombre_libro; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="estanteria" class="form-label">Estantería:</label>
                <input type="text" class="form-control" id="estanteria" name="nueva_estanteria" value="<?php echo $estanteria; ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-select" id="estado" name="nuevo_estado">
                    <option value="Disponible" <?php if($estado == 'Disponible') echo 'selected'; elseif($estado != 'Retirado') echo 'disabled'; ?>>Disponible</option>
                    <option value="Retirado" <?php if($estado == 'Retirado') echo 'selected'; ?>>Retirado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Aplicar Cambios</button>
            <a href="verejemplares.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>