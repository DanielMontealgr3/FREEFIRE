<?php

    include("../../database/database.php");
    $conexion = new database;
    $con = $conexion->conectar();
    session_start();
    
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $id_usuario = $_SESSION['id_usuario'];

    if(!isset($username) || !isset($id_usuario)){
        header("Location: ../../index.php");
    }

    $sql = $con->prepare("SELECT *
FROM armas
INNER JOIN usuario ON usuario.nivel >= armas.nivelRequerido
INNER JOIN categoriadearmas ON categoriadearmas.id_categoria = armas.id_categoria
WHERE usuario.id_usuario = $id_usuario;
");
    $sql->execute();
    $consulta = $sql->fetchAll(PDO::FETCH_ASSOC);

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armas</title>
    <link rel="stylesheet" href="css/armas.css"> 
</head>
<body>
    
<nav>
        <div class="nav-container">
            <a href="./lobby.php" class="brand"><img width="150px" height="30px" src="../../assets//img/ff-logo.png" alt=""></a>
            <div class="nav-items">
                
                <a href="./lobby.php" class="nav-item">
                    <div class="icon-container">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <span class="nav-text">Lobby</span>
                </a>
                
                
                <a href="#" class="nav-item">
                    <div class="icon-container">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <span class="nav-text">Armas</span>
                </a>
                
                
                <a href="./historialPartidas/partidas.php" class="nav-item">
                    <div class="icon-container">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5h3a2.5 2.5 0 0 1 2.5 2.5V9"></path>
                            <path d="M6 9h12"></path>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5h-3a2.5 2.5 0 0 0-2.5 2.5V9"></path>
                            <path d="M12 12v3"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </div>
                    <span class="nav-text">Partidas</span>
                </a>
            </div>
        </div>
    </nav>


    <main>
        <section class="armas">
        <?php 
        foreach ($consulta as $resu) {
        ?>
            <div class="card">
                <div  class="titulo">
                    <h2 class="nmobreDelArma"><?php echo $resu['nombreArma'] ?> </h2>
                    <div style="display: flex;">
                        <img height="20px" width="20px"  src="./armas/municion.png" alt="">
                        <p class="balas"><?php echo $resu['balas'] ?></p>
                    </div>
                </div>


                <div class="imagen">
                    <img class="imgArma" src="armas/<?php echo $resu['foto'] ?>" alt="">
                </div>


                <div class="descripcion">
                    <p class="nombreCategoria"><?php echo $resu['nombreCategoria'] ?></p>
                </div>


            </div>
        <?php
        } ?>
        </section>

    </main>
</body>
</html>