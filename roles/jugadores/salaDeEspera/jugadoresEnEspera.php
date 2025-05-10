<?php


include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$idSala = json_decode(file_get_contents('php://input', true));


$consulta = $con->prepare("SELECT * FROM jugadores_en_sala 
                          INNER JOIN usuario ON usuario.id_usuario = jugadores_en_sala.id_usuario
                          INNER JOIN personaje on personaje.id_personaje = usuario.personaje
                          WHERE id_sala = ?");
$consulta->execute([$idSala]);
$jugadores = $consulta->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($jugadores);