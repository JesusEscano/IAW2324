<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Finalizar la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>