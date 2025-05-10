<?php
include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();

$consulta = $con->prepare('
    SELECT id_sala, COUNT(*) as jugadores 
    FROM jugadores_en_sala 
    GROUP BY id_sala
');
$consulta->execute();
$jugadores = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jugadores);
?>
    