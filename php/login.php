<?php
if (isset($_POST['nombre']) && !empty($_POST['nombre']) 
&& isset($_POST['contraseña']) && !empty($_POST['contraseña'])) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $contraseña = $_POST['contraseña'];
    if ($nombre=='admin' && $contraseña=='H4CK3R4$1R') {
        echo "<p>Acceso concedido</p>";    
    }
    else {echo "Acceso denegado";}
} else {
    echo "<p>Por favor, escriba su nombre y contraseña</p>";
}
?>