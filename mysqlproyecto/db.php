<?php
$host = 'sql305.thsite.top';   
$user = 'thsi_35757315';   
$pass = "3bdDUYsw";   
$database = 'thsi_35757315_incidencias';     
$conn = mysqli_connect($host,$user,$pass,$database);   
if (!$conn) {                                             
    die("Conexión fallida con base de datos: " . mysqli_connect_error());     
  }
?>