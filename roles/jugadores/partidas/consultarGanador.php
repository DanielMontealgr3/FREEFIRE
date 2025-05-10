<?php 

include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();



$datos = json_decode(file_get_contents('php://input',true));



$consulta = $con->prepare("SELECT * FROM `jugadores_en_sala`
INNER JOIN usuario on usuario.id_usuario = jugadores_en_sala.id_usuario
WHERE $datos");
$consulta->execute();
$usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['succes' => true, 'usuario' => $usuarios ]);