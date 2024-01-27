<?php include "header.php" ?>
<div class="container mt-5">
    <h1 class="text-center"> Gestión simple de incidencias</h1>
        <p class="text-center">
            Ejemplo con sesiones para implementar un CRUD en PHP con MySQL.
            Está implementado:
            -Tablas de plantas y aulas 
            -Barra de navegación con contador
            -Panel de administración para crear usuarios de dirección y profesores
            -Sólo los administradores y dirección tienen permisos para añadir o borrar incidencias
        </p>
  <div class="container">
    <form action="login.php" method="post">
        <div class="from-group text-center">
            <input type="submit" class="btn btn-primary mt-2" value="¡Al lío!">
        </div>
    </form>
  </div>
</div>
<?php include "footer.php" ?>