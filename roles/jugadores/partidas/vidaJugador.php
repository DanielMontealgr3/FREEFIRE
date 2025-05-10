<?php 


include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = $_SESSION['id_usuario'];

$consultaVida = $con->prepare("SELECT * FROM usuario WHERE id_usuario = :id_usuario");
$consultaVida->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$consultaVida->execute();

$vida = $consultaVida->fetch(PDO::FETCH_ASSOC);

echo json_encode($vida);

?>