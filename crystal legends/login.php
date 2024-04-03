<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crystal Legends</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="main">
        <img src="imagenes/cl logo grande.png" alt="logo"> 
        <input type="checkbox" id="chk" name="chk">
        <div class="login">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                <label for="chk" aria-hidden="true">Iniciar sesión</label>
                <input type="text" id="nombrelogin" name="nombrelogin" placeholder="Nombre de usuario" required="">
                <input type="password" id="contraseñalogin" name="contraseñalogin" placeholder="Contraseña" required="">
                <button type="submit" name="action" value="login">Jugar</button>
                <p><a href="recuperar.php">Recuperar contraseña</a></p>
                <div class="error" id="error"></div>
                <div class="errorregi" id="errorregi"></div>
                <div class="exito" id="exito"></div>
            </form>
        </div>
        <div class="registra">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                <label for="chk" aria-hidden="true">Regístrate</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre de usuario" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" required="">
                <input type="password" id="contraseña2" name="contraseña2" placeholder="Repita contraseña" required="">
                <button type="submit" name="action" value="register">Registrar</button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "login") {
                $usuario = htmlspecialchars($_POST["nombrelogin"]);
                $contrasena = htmlspecialchars($_POST["contraseñalogin"]);
                include_once 'bd.php';
                if ($conn) {
                    $query = "SELECT * FROM usuarios WHERE usuario=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $usuario);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        $hash_contraseña = $row['password'];
                        $perfil = $row['perfil'];
                        $id = $row['id'];
                        if (password_verify($contrasena, $hash_contraseña)) {
                            session_start();
                            $_SESSION["user"] = $usuario;
                            $_SESSION["id"] = $id;
                            $_SESSION["perfil"] = $perfil;
                            header("location: menu.php");
                            exit();
                        } else {
                            $mensajecontra = "Acceso denegado, contraseña incorrecta";
                            $mensajecontra = htmlspecialchars($mensajecontra, ENT_QUOTES, 'UTF-8');
                            echo "<script>document.getElementById('error').innerHTML = '<p class=\"text-center\">$mensajecontra</p>';</script>";
                        }
                    } else {
                        $mensajeUsu = "Acceso denegado, el usuario no existe";
                        $mensajeUsu = htmlspecialchars($mensajeUsu, ENT_QUOTES, 'UTF-8');
                        echo "<script>document.getElementById('error').innerHTML = '<p class=\"text-center\">$mensajeUsu</p>';</script>";
                    }
                }
            } elseif ($_POST["action"] == "register") {
                // Procesamiento del registro de usuario
                $usuario = htmlspecialchars($_POST["nombre"]);
                $email = htmlspecialchars($_POST["email"]);
                $contrasena = htmlspecialchars($_POST["contraseña"]);
                $contrasena2 = htmlspecialchars($_POST["contraseña2"]);

                if ($contrasena != $contrasena2) {
                    echo "<script>document.getElementById('errorregi').innerHTML = '<p class=\"text-center\">Las contraseñas no coinciden</p>';</script>";
                    exit();
                }

                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

                include_once 'bd.php';
                if ($conn) {
                    $query = "INSERT INTO usuarios (usuario, password, mail) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $usuario, $hashed_password, $email);
                    if ($stmt->execute()) {
                        echo "<script>document.getElementById('exito').innerHTML = '<p class=\"text-center\">Usuario registrado, inicia sesión</p>';</script>";
                    } else {
                        echo "<script>document.getElementById('errorregi').innerHTML = '<p class=\"text-center\">Error al registrar el usuario</p>';</script>";
                    }
                    $stmt->close();
                    $conn->close();
                }
            }
        }
    }
    ?>
</body>
</html>