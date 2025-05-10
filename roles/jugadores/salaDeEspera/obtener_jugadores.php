<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();

if (!isset($_GET['id_sala'])) {
    echo json_encode(["error" => "Sala no especificada"]);
    exit;
}

$id_sala = $_GET['id_sala'];

$consulta = $con->prepare("SELECT usuario.username, usuario.personaje, jugadores_en_sala.listo 
                          FROM jugadores_en_sala 
                          INNER JOIN usuario ON usuario.id_usuario = jugadores_en_sala.id_usuario
                          WHERE id_sala = ?");
$consulta->execute([$id_sala]);
$jugadores = $consulta->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($jugadores);
exit;
