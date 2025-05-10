<?php   
include("../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id = $_POST['id'];
$userId = $_POST['userId'];

// Actualizar el personaje en la tabla usuario
$update = $con->prepare("UPDATE usuario SET personaje = :id WHERE id_usuario = :userId");
$update->bindParam(':id', $id);
$update->bindParam(':userId', $userId);
$update->execute();

// Consultar el nombre y la imagen del personaje seleccionado
$consultaPersonaje = $con->prepare("SELECT nombrePersonaje, imagen FROM personaje WHERE id_personaje = :id");
$consultaPersonaje->bindParam(':id', $id);
$consultaPersonaje->execute();
$personaje = $consultaPersonaje->fetch(PDO::FETCH_ASSOC);

// Preparar la respuesta como JSON
$respuesta = array();

if($personaje) {
    $respuesta['mensaje'] =  $personaje['nombrePersonaje'];
    $respuesta['imagen'] = $personaje['imagen'];
} else {
    $respuesta['mensaje'] = "No se encontró el personaje";
    $respuesta['imagen'] = "assets/img/default.png"; // Imagen por defecto
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($respuesta);
?>