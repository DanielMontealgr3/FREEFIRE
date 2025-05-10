<?php 

include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = json_decode(file_get_contents('php://input', true));



$consulta = $con->prepare("SELECT * FROM usuario WHERE id_usuario = $id_usuario");
$consulta->execute();
$vida = $consulta->fetch(PDO::FETCH_ASSOC);

if($vida['puntos'] < 500){
    $actualizarNivel = $con->prepare("UPDATE usuario SET  nivel = 1 WHERE id_usuario = $id_usuario ");
    $actualizarNivel->execute();
}else if($vida['puntos'] >= 500){
    $actualizarNivel = $con->prepare("UPDATE usuario SET  nivel = 2 WHERE id_usuario = $id_usuario");
    $actualizarNivel->execute();
}

echo json_encode($vida);