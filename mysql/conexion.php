<?php

    // Conexion con la base de datos
    header("Content-type:text/html;charset=utf-8");
    $enlace = mysqli_connect("database-5014818324.webspace-host.com","dbu2458655","Adell83IES","dbs12312372");
    if (!$enlace) {
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
        echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    }
    else
    {
        echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos de Jesús es genial." . PHP_EOL;
        echo "Información del host: " . mysqli_get_host_info($enlace) . PHP_EOL;
    }
    mysqli_close($enlace);
?>