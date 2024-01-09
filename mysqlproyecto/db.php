<?php
$host = 'database-5014818324.webspace-host.com';   
$user = 'dbu2458655';   
$pass = "Adell83IES";   
$database = 'dbs12312372';     
$conn = mysqli_connect($host,$user,$pass,$database);   
if (!$conn) {                                             
    die("Conexión fallida con base de datos: " . mysqli_connect_error());     
  }
?>