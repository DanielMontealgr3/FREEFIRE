<?php 
include("../../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$codigo = $_SESSION['codigo'];


if (!isset($codigo)) {
    echo "<script>window.location = '../../../../session/destruirSession.php'</script>";
}

$consulta = $con->prepare("SELECT ganador FROM partida WHERE codigoPartida = :codigo");
$consulta->bindParam(":codigo",$codigo,PDO::PARAM_INT);
$consulta->execute();

$ganador = $consulta->fetchColumn();

$consultaUsuario = $con->prepare("SELECT * FROM usuario
 INNER JOIN  personaje on personaje.id_personaje = usuario.personaje WHERE id_usuario = $ganador");
$consultaUsuario->execute();
$usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);
echo json_encode($usuario);
?>