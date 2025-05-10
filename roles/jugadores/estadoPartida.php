<?php 
include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();


$data = json_decode(file_get_contents("php://input",true));

$selectEstado = $con->prepare("SELECT estado FROM salas WHERE id_sala = $data");
$selectEstado->execute();
$infoEstado = $selectEstado->fetchColumn();

echo json_encode($infoEstado);

?>