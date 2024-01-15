<?php session_start()?>
<?php if($_SESSION['user']){
}else{
 header("Location: ../login.php");
}?>
<?php include "../header.php" ?>
<?php 
     if(isset($_GET['eliminar']))
     $conectado = $_SESSION['user'];
     $queryc = "SELECT * FROM usuarios WHERE usuario=?";
     $stmt = $conn->prepare($queryc);
     $stmt->bind_param("s", $usuario);
     $stmt->execute();
     $result = $stmt->get_result();

     if ($result->num_rows == 0)
     {
        header("Location: ../login.php");
     }
     else
     {
         $id= htmlspecialchars($_GET['eliminar']);
         $query = "DELETE FROM incidencias WHERE id = {$id}"; 
         $delete_query= mysqli_query($conn, $query);
         // header("Location: home.php");
         echo "<script>window.location='home.php';</script>";
     }
?>
<?php include "../footer.php" ?>