<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['perfil'] !== 'administrador' || !isset($_SESSION['token'])) {
    header("Location: ../login.php");
    exit();
}

include "../header.php";
include "login.php";

$usuario_activo = $_SESSION['user'];
$query_usuario = "SELECT id FROM usuarios WHERE usuario = '$usuario_activo'";
$resultado_usuario = mysqli_query($conn, $query_usuario);

if ($resultado_usuario) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $usuario_id = $fila_usuario['id'];
} else {
    die("Error al obtener el ID del usuario: " . mysqli_error($conn));
}

$mensaje = ""; // Lo que se mostrará al realizar la acción

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $password = base64_encode($_POST['password']);
    $perfil = filter_var($_POST['perfil'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE mail = ? OR usuario = ?");
    $stmt->bind_param("ss", $mail, $nombre);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "Error: El usuario ya existe.";
    } else {
        $sql = "INSERT INTO usuarios (usuario, password, perfil, mail) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $password, $perfil, $mail);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario registrado con éxito.";

            $to = $mail;
            $subject = "Registro Exitoso";
            $escrito = "Gracias por registrarte.";
            $headers = "From: jesusescano83@iesamachado.org";

            mail($to, $subject, $escrito, $headers);
        } else {
            $mensaje = "Error al registrar el usuario.";
        }
    }
    $conn->close();
}
?>

<section>
<div class="container mt-5 pt-5">
    <div class="row">
    <div class="col-12 col-sm-8 col-md-6 m-auto">
    <div class="card border-0 shadow">
    <div class="card-body">
    <img src="../IESlogo.jpg" alt="IES Antonio Machado" style="display: block; margin: 0 auto; margin-bottom: 20px;">
    <h2 style="display: block; margin: 0 auto; margin-bottom: 20px; text-align: center;">AÑADIR USUARIO</h2>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
<div class="form-group">
        <h4 class="mb-0"><label for="nombre" class="form-label">Usuario:</label></h4>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
<div class="form-group">
        <h4 class="mb-0"><label for="password" class="form-label">Contraseña:</label></h4>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
<div class="form-group">
        <h4 class="mb-0"><label for="perfil" class="form-label">Perfil:</label></h4>
        <select class="form-control" id="perfil" name="perfil" required>
            <option value="administrador">Administrador/a</option>
            <option value="direccion">Dirección</option>
            <option value="profesor">Profesor/a</option>
        </select>
    </div>
<div class="form-group">
        <h4 class="mb-0"><label for="email" class="form-label">E-Mail:</label></h4>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="text-center mt-3" id="mensajera"></div>
<div class="form-group text-center">
        <input type="submit" name="crear" class="btn btn-primary mt-2" value="Añadir">
    </div>
</form>
<script>document.getElementById("mensajera").innerHTML = "<?php echo '<strong>' . $mensaje . '</strong>'; ?>";</script>    
</div>
</div> 
</div>
</div>
</div>
</div>
</section>
</div>
  </div>
  <div class="container text-center mt-5">
    <a href="home.php" class="btn btn-warning mt-5"> Volver </a>
  <div>
<?php include "../footer.php" ?>