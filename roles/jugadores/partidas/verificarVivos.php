<?php 
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$data = json_decode(file_get_contents('php://input', true));

$consulta = $con->prepare("SELECT *  FROM  jugadores_en_sala WHERE id_sala = $data");
$consulta->execute();
$verificar = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($verificar);