<?php include "db.php" ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
<!-- Bootstrap Icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<!-- Mi CSS -->
<style>
table, th, td {
text-align: center;
}
.celdasinfo {
font-size: 18px;
padding: 10px;
}

.celdaptes {
background-color: #FF5733 !important;
color: white;
}

.celdartas {
background-color: #7DCEA0 !important;
color: white;
}

.celdatotal {
background-color: #3498DB !important;
color: white;
}
    </style>
    <title>Gestión de incidencias (CRUD)</title>
</head>
<body>
    
