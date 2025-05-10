<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

if (!isset($_SESSION['id_usuario']) || !isset($_POST['id_sala'])) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_sala = $_POST['id_sala'];

// Obtener estado actual del usuario
$consulta = $con->prepare("SELECT listo FROM jugadores_en_sala WHERE id_usuario = ? AND id_sala = ?");
$consulta->execute([$id_usuario, $id_sala]);
$estado = $consulta->fetch(PDO::FETCH_ASSOC);

// Si no está en la sala, no actualizar
if (!$estado) {
    echo json_encode(["success" => false, "error" => "Usuario no encontrado en la sala"]);
    exit;
}

// Alternar el estado de 'listo' (1 -> 0, 0 -> 1)
$nuevoEstado = $estado['listo'] ? 0 : 1;
$actualizar = $con->prepare("UPDATE jugadores_en_sala SET listo = ? WHERE id_usuario = ? AND id_sala = ?");
$actualizar->execute([$nuevoEstado, $id_usuario, $id_sala]);

echo json_encode(["success" => true, "listo" => $nuevoEstado]);
?>
