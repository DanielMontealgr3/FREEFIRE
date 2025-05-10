<?php 

include("../../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = json_decode(file_get_contents('php://input',true));

echo json_encode($id_usuario);


$sacarUsuario = $con->prepare("DELETE FROM `jugadores_en_sala` WHERE id_usuario =  $id_usuario");
$sacarUsuario->execute();


?>