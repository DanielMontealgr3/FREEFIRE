<?php

    require_once("../../database/database.php");
    $conexion = new database;
    $con = $conexion->conectar();
    session_start();

    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $id_usuario = $_SESSION['id_usuario'];
    include('menu.html');  


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>administrador</title>
</head>
<body>
<div class="contenedor_principal">
    <div class="contenedor_body">
    
        <form>
        <h1 class="bienvenida">Bienvenido, <?php echo htmlspecialchars($username); ?></h1>
        <!-- Icono de usuario -->
        <div class="iconos">
            <i class="fas fa-house"></i>
        </div>
            
        </form>
        
    </div>
</div>
    
    

</body>
</html>