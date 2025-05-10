<?php

include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = $_SESSION['id_usuario'];
$idSala = json_decode(file_get_contents('php://input', true));


$consulta = $con->prepare("SELECT vida FROM usuario WHERE id_usuario = $id_usuario");
$consulta->execute();
$usuarios = $consulta->fetch(PDO::FETCH_ASSOC);


echo json_encode($usuarios);
