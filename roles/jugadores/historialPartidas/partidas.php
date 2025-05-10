<?php

include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];

if (!isset($username) || !isset($id_usuario)) {
    header("Location: ../../../session/destruirSession.php");
}

$consulta = $con->prepare("SELECT 
    partida.*, 
    usuario.*,  
    salas.*, 
    mundos.*, 
    ganador.id_usuario AS id_ganador, 
    ganador.username AS nombre_ganador
FROM partida
INNER JOIN usuario ON usuario.id_usuario = partida.id_usuario
INNER JOIN salas ON salas.id_sala = partida.id_sala
INNER JOIN mundos ON mundos.id_mundo = salas.id_mundo
INNER JOIN usuario AS ganador ON ganador.id_usuario = partida.ganador 
WHERE usuario.id_usuario = $id_usuario AND  tiempo >= DATE_SUB(NOW(), INTERVAL 3 DAY) ORDER BY `partida`.`tiempo` DESC ");
$consulta->execute();

$info = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidas</title>
    <link rel="stylesheet" href="partidas.css">
</head>

<body>

    <div class="titulo">



        <p class="tituloPartida">Historial De Partida</p>

    </div>

    <div class="opcionesDiv">
        <a href="../../jugadores/lobby.php">
            <div class="opciones">
                <div class="nombreOpciones">
                    <img class="imgIconPersona" src="../../../assets/img/user (1).png" alt="">
                    <h2>Lobby</h2>

                </div>
            </div>

            <a href="../armas.php">
                <div class="opciones">
                    <div class="nombreOpciones">
                        <img class="imgIcon" src="../../../assets/img/logopistola.png" alt="">
                        <h2>Armas</h2>

                    </div>
                </div>

                <a href="../historialPartidas/partidas.php">
                    <div class="opciones">
                        <div class="nombreOpciones">
                            <img class="imgControl" src="../../../assets/img/consola (1).png" alt="">
                            <h2>Partidas</h2>

                        </div>
                    </div>
                </a>
    </div>

    <section class="partidas">

        <?php


        foreach ($info as $resu) {

        ?>

            <div class="registro">

                <div class="colum1">
                    <hr style="height: auto ; background-color:green">
                    <img class="mapa" src="../mundos/<?php echo $resu['fotoMapa'] ?>" alt="">
                    <div>
                        <p class="fecha">Fecha: <?php echo $resu['tiempo'] ?> </p>
                        <p class="nombreMapa">Mapa : <?php echo $resu['nombreMundo'] ?></p>

                    </div>
                </div>

                <div class="colum2">
                    <p class="ganador">Ganador: <?php echo $resu['nombre_ganador'] ?></p>
                    <p class="sala">Sala: <?php echo $resu['id_sala'] ?></p>
                </div>

                <div class="colum3">
                    <p class="jugadores">Jugadores: </p>
                    <?php

                    $consultaUsuarios = $con->prepare("SELECT * FROM partida INNER JOIN usuario on usuario.id_usuario = partida.id_usuario
                     WHERE codigoPartida = $resu[codigoPartida]");
                    $consultaUsuarios->execute();
                    $datos = $consultaUsuarios->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($datos as $fila) {
                    ?>

                        <p class="username"> <?php echo $fila['username'] ?></p>


                    <?php } ?>
                </div>

            </div>
        <?php } ?>
    </section>

</body>

</html>