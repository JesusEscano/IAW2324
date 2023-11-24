<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores numéricos</title>
</head>
<body>
<?php
    // Definición de variables
    $a = 5;
    $b = 3;

    // Suma
    $suma = $a + $b;
    echo "<p>Suma: $a + $b = $suma</p>";
    
    // Resta
    $resta = $a - $b;
    echo "<p>Resta: $a - $b = $resta</p>";
    
    // Multiplicación
    $multi = $a * $b;
    echo "<p>Multiplicación: $a * $b = $multi</p>";
    
    // División
    $division = $a / $b;
    echo "<p>División: $a / $b = $division</p>";
    
    // Resto
    $resto = $a % $b;
    echo "<p>Resto de la división: $a % $b = $resto</p>";
    
    // Incremento
    $a++;
    echo "<p>El número siguiente de 5 es $a</p>";
    
    // Decremento
    $b--;
    echo "<p>El número anterior a 3 es $b</p>";

    // POTEEEEEENCIA
    $potencia = $a ** $b;
    echo "</p>El resultado de elevar $a a $b es $potencia</p>";

    //Raíz
    $raiz = pow($a, 1/$b);
    echo "<p>El resultado de hacer la raíz $b-ésima de $a es $raiz</p>";

    //+=
    $nose = $a+=;
    echo "<p>Lo que da el másigual es $nose</p>";
    ?>
</body>
</html>