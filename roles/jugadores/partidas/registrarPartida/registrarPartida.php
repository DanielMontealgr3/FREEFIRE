<?php
include("../../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];
$codigo = $_SESSION['codigo'];

if (!isset($username) || !isset($id_usuario)) {
    echo "<script>window.location = '../../../../session/destruirSession.php'</script>";
}

$id_sala = json_decode(file_get_contents('php://input'), true);

$consultaExistente = $con->prepare("SELECT COUNT(*) FROM partida WHERE id_usuario = :id_usuario AND codigoPartida = :codigo");
$consultaExistente->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
$consultaExistente->bindParam(":codigo", $codigo, PDO::PARAM_INT);
$consultaExistente->execute();

$existeRegistro = $consultaExistente->fetchColumn();

if ($existeRegistro == 0) {
    $jugadoresSala = $con->prepare("INSERT INTO partida(id_usuario, codigoPartida, id_sala) VALUES(:id_usuario, :codigo, :id_sala)");
    $jugadoresSala->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
    $jugadoresSala->bindParam(":codigo", $codigo, PDO::PARAM_INT);
    $jugadoresSala->bindParam(":id_sala", $id_sala, PDO::PARAM_INT);
    $jugadoresSala->execute();
}

if (!empty($_SESSION['ganador'])) {
    $actualizarGanador = $con->prepare("UPDATE partida SET ganador = :id_usuario WHERE codigoPartida = :codigo");
    $actualizarGanador->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
    $actualizarGanador->bindParam(":codigo", $codigo, PDO::PARAM_INT);
    $actualizarGanador->execute();
}
?>