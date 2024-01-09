<?php

    // Conexion con la base de datos
    header("Content-type:text/html;charset=utf-8");
    $enlace = mysqli_connect("sql305.thsite.top","thsi_35757315","3bdDUYsw","thsi_35757315_bdpruebas");
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