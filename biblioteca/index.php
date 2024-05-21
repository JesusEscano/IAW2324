<?php
include_once 'bd.php'; // Incluir archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se enviaron datos de inicio de sesión
    if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
        // Limpiar y validar los datos de inicio de sesión
        $correo = mysqli_real_escape_string($conn, $_POST['correo']);
        $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

        // Consulta para verificar las credenciales de inicio de sesión y que el usuario esté activo
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena' AND activo = 1";
        $resultado = mysqli_query($conn, $sql);

        if (mysqli_num_rows($resultado) == 1) {
            // Usuario autenticado correctamente
            $fila = mysqli_fetch_assoc($resultado);
            session_start(); // Iniciar sesión
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['nombre_usuario'] = $fila['nombre_usuario'];
            $_SESSION['perfil'] = $fila['perfil'];

            // Redirigir según el perfil del usuario
            if ($fila['perfil'] == 'Usuario') {
                header("Location: home.php");
                exit();
            } elseif ($fila['perfil'] == 'Ayudante' || $fila['perfil'] == 'Administrador') {
                header("Location: añadirlibro.php");
                exit();
            }
        } else {
            // Credenciales incorrectas o usuario no activo
            $error = "Correo o contraseña incorrectos o cuenta desactivada.";
        }
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>    
    <link rel="shortcut icon" href="media/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="login.css">
    <title>Biblioteca IES Antonio Machado</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div id="cont-main">
        <div id="fondo-blanco">
            <h1>Biblioteca</h1>
            <p>IES Antonio Machado</p>
        </div>
        <div id="cont-bienvenida">
            <p id="p1">Lee</p>
            <p id="p2">Imagina</p>
            <p id="p3">Vive</p>
        </div>
        <div id="cont-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input id="i_correo" type="text" name="correo" placeholder="Correo o usuario">
                <input id="i_contrasena" type="password" name="contrasena" placeholder="Contraseña">
                <p id="error"><?php if(isset($error)) echo $error; ?></p>
                <p id="cont-activar-cuenta">¿No puedes acceder?<a href="activar-cuenta"> Activa tu cuenta</a></p>
                <button id="button-entrar" type="submit">Entrar</button>
            </form>
        </div>
    </div>
    <script src="test-user.js"></script>
</body>
</html>