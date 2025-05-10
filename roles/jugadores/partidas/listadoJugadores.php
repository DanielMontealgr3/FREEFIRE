<?php 

include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$idSala = json_decode(file_get_contents('php://input'), true);

$id_usuario = $_SESSION['id_usuario'];
$jugadores = $con->prepare("SELECT * FROM jugadores_en_sala 
INNER JOIN usuario on usuario.id_usuario = jugadores_en_sala.id_usuario 
INNER JOIN personaje on personaje.id_personaje = usuario.personaje
WHERE id_sala = $idSala AND usuario.id_usuario != ?");

$jugadores->execute([$id_usuario]);
$listaDeJugadores = $jugadores->fetchAll(PDO::FETCH_ASSOC);


echo json_encode(['success' => true, "jugadores" => $listaDeJugadores]);

?>