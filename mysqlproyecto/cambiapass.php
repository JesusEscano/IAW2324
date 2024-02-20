<?php include "header.php" ?><div class="fondo">
<section>
<div class="container mt-5 pt-5">
    <div class="row">
    <div class="col-12 col-sm-8 col-md-6 m-auto">
    <div class="card border-0 shadow">
    <div class="card-body">
    <img src="IESlogo.jpg" alt="IES Antonio Machado" style="display: block; margin: 0 auto; margin-bottom: 20px;">
    <h2 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">REPORTE DE INCIDENCIAS</h2>
    <h3 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">CAMBIO DE CONTRASEÑA</h3>
    <svg style="display: block; margin: 0 auto; margin-bottom: 20px;" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
     </svg>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <input type="text" id="nombre" name="nombre" placeholder="Usuario" class="form-control my-4 py-2" style="text-align: center" required></p>
    <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" class="form-control my-4 py-2" style="text-align: center"  required></p>
    <input type="hidden" name="token" value="<?php echo bin2hex(random_bytes(32)); ?>">
    <div class="text-center mt-3">
    <input type="submit" class="btn btn-primary mt-2" name="submit" value="Cambiar contraseña">
</form>
<?php
if ($_POST && isset($_POST["submit"])) {
    $usuario = htmlspecialchars($_POST["nombre"]);
    $contrasena = htmlspecialchars($_POST["contraseña"]);
    $token = htmlspecialchars($_POST["token"]);
    include_once 'db.php';
    if ($conn){
        $query = "SELECT * FROM usuarios WHERE usuario=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hash_contraseña = base64_decode($row['password']);
            $id = $row['id'];
            if ($contrasena == $hash_contraseña) {
                // Mostrar formulario de cambio de contraseña
?>
<h4> </h4>
<h4 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">Indique la nueva contraseña</h4>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <input type="password" id="nueva_contraseña" name="nueva_contraseña" placeholder="Nueva Contraseña" class="form-control my-4 py-2" style="text-align: center" required></p>
    <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" placeholder="Confirmar Contraseña" class="form-control my-4 py-2" style="text-align: center" required></p>
    <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
    <div class="text-center mt-3">
    <input type="submit" class="btn btn-primary mt-2" name="submit_cambio" value="Confirmar cambio">
</form>
<?php
            } else {
                $mensajecontra = "Acceso denegado, contraseña incorrecta";
                $mensajecontra = htmlspecialchars($mensajecontra, ENT_QUOTES, 'UTF-8');
                echo "<div id='error' class='text-center mt-3'><p>$mensajecontra</p></div>";
            }
        } else {
            $mensajeUsu = "Acceso denegado, el usuario no está en la base de datos";
            $mensajeUsu = htmlspecialchars($mensajeUsu, ENT_QUOTES, 'UTF-8');
            echo "<div id='error' class='text-center mt-3'><p>$mensajeUsu</p></div>";
        }
        
        $stmt->close();
        $conn->close();
    }   
}
elseif ($_POST && isset($_POST["submit_cambio"])) {
    $nueva_contraseña = htmlspecialchars($_POST["nueva_contraseña"]);
    $confirmar_contraseña = htmlspecialchars($_POST["confirmar_contraseña"]);
    $id_usuario = $_POST["id_usuario"];
    
    if ($nueva_contraseña == $confirmar_contraseña) {
        $hash_nueva_contraseña = base64_encode($nueva_contraseña);
        include_once 'db.php';
        if ($conn){
            $query_update = "UPDATE usuarios SET password=? WHERE id=?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param("si", $hash_nueva_contraseña, $id_usuario);
            $stmt_update->execute();
            
            echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 3000);</script>";
            echo "<div id='mensaje_cambio' class='text-center mt-3'><strong><p>La contraseña ha sido cambiada exitosamente. Serás redirigido al inicio de sesión en 3 segundos.</p></strong></div>";
            
            $stmt_update->close();
            $conn->close();
        }
    } else {
        echo "<div id='error' class='text-center mt-3'><p>Las contraseñas no coinciden.</p></div>";
    }
}
?>
<div class="text-center mt-3"><?php include "footer.php" ?></div>