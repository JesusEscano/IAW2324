<?php include "header.php" ?><div class="fondo">
<section>
<div class="container mt-5 pt-5">
    <div class="row">
    <div class="col-12 col-sm-8 col-md-6 m-auto">
    <div class="card border-0 shadow">
    <div class="card-body">
    <img src="IESlogo.jpg" alt="IES Antonio Machado" style="display: block; margin: 0 auto; margin-bottom: 20px;">
    <h2 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">REPORTE DE INCIDENCIAS</h2>
    <svg style="display: block; margin: 0 auto; margin-bottom: 20px;" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
     </svg>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
    <input type="text" id="nombre" name="nombre" placeholder="Usuario" class="form-control my-4 py-2" style="text-align: center" required></p>
    <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" class="form-control my-4 py-2" style="text-align: center"  required></p>
    <div class="text-center mt-3">
    <input type="submit" class="btn btn-primary mt-2" name="submit" value="Iniciar sesión">
</form>
<div class="text-center mt-3" id="error"></div>
</div>
</div> 
</div>
</div>
</div>
</div>
</section>
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
            $perfil = $row['perfil'];
            if ($hash_contraseña === $contrasena) {
                if ($perfil === 'administrador') {
                    session_start();
                    $_SESSION["user"] = $usuario;
                    header("location: includes/home.php");
                } else {
                    session_start();
                    $_SESSION["user"] = $usuario;
                    header("location: includes/incidenciasabiertas.php");
                }
            } else {
                $mensajecontra = "Acceso denegado, contraseña incorrecta";
                $mensajecontra = htmlspecialchars($mensajecontra, ENT_QUOTES, 'UTF-8');
                echo "<script>document.getElementById('error').innerHTML = '<p class=\"text-center\">$mensajecontra</p>';</script>";
            }
        } else {
            $mensajeUsu = "Acceso denegado, el usuario no está en la base de datos";
            $mensajeUsu = htmlspecialchars($mensajeUsu, ENT_QUOTES, 'UTF-8');
            echo "<script>document.getElementById('error').innerHTML = '<p class=\"text-center\">$mensajeUsu</p>';</script>";
        }
        
        $stmt->close();
        $conn->close();
    }   
}
?>
<div class="text-center mt-3"><?php include "footer.php" ?></div>
