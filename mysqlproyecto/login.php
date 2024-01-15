<?php include "header.php" ?>
<div class="container mt-5">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
<p class="text-center"><input type="text" id="nombre" name="nombre" placeholder="Usuario" required></p>
<p class="text-center"><input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" required></p>
<p class="text-center"><input type="submit" class="btn btn-primary mt-2" name="submit" value="Iniciar sesión"></p>
</form>
  </div>
  <?php
if ($_POST){
    $usuario = htmlspecialchars($_POST["nombre"]);
    $contrasena = htmlspecialchars($_POST["contraseña"]);
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
            if ($hash_contraseña === $contrasena) {
                session_start();
                $_SESSION["user"] = $usuario;
                header("location: includes/home.php");
            } else {
                echo "<p class='text-center'>Acceso denegado, contraseña incorrecta</p>";
            }
        } else {
            echo "<p class='text-center'>Acceso denegado, el usuario no está en la base de datos</p>";
        }
        
        $stmt->close();
        $conn->close();
    }   
}
?>
<?php include "footer.php" ?>
