<?php

require_once("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();
include('menu.html');
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ESTADISTICAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="contenedor_principal">
        <div class="contenedor_body">

            <form>
                <h1 class="titulo">ESTADISTICAS DE USUARIOS</h1>
                <table border="1">
                    <tr>
                        <th>ID_PARTIDA</th>
                        <th>ID_USUARIO</th>
                        <th>USERNAME</th>
                        <th>TIEMPO</th>
                        <th>GANADOR</th>
                        <th>CODIGO PARTIDA</th>
                        <th>SALA</th>

                    </tr>

                    <?php



                    $sql = $con->prepare("SELECT 
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
                                    INNER JOIN usuario AS ganador ON ganador.id_usuario = partida.ganador    ORDER BY `partida`.`tiempo` DESC ");
                    $sql->execute();
                    $fila = $sql->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fila as $resu) {
                    ?>

                        <tr>

                            <td class="texto_registros"><?php echo $resu['id_partida']; ?></td>
                            <td class="texto_registros"><?php echo $resu['id_usuario']; ?></td>
                            <td class="texto_registros"><?php echo $resu['username']; ?></td>
                            <td class="texto_registros"><?php echo $resu['tiempo']; ?></td>
                            <td class="texto_registros"><?php echo ($resu['ganador'] == $resu['id_usuario']) ? "Ganador" : ""; ?></td>
                            <td class="texto_registros"><?php echo $resu['codigoPartida']; ?></td>
                            <td class="texto_registros"><?php echo $resu['id_sala']; ?></td>
                            <?php

                    $consultaUsuarios = $con->prepare("SELECT * FROM partida INNER JOIN usuario on usuario.id_usuario = partida.id_usuario
                     WHERE codigoPartida = $resu[codigoPartida]");
                    $consultaUsuarios->execute();
                    $datos = $consultaUsuarios->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($datos as $fila) {
                    ?>

                        <td class="texto_registros"> <?php echo $fila['username'] ?></td>


                    <?php } ?>





                        </tr>

                    <?php
                    }
                    ?>
                </table>

            </form>
        </div>
    </div>

</body>

</html>