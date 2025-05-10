<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();

header('Content-Type: application/json');

try {
    $datos = json_decode(file_get_contents('php://input', true));

    if (!isset($datos->idArma)) {
        throw new Exception("idArma is missing in the request body.");
    }

    $idArma = $datos->idArma;

    // Validate that $idArma is an integer
    if (!is_numeric($idArma)) {
        throw new Exception("idArma must be an integer.");
    }


    $consultaPartesCuerpo = $con->prepare("SELECT * FROM zonaataque");
    $consultaPartesCuerpo->execute();
    $partesCuerpo = $consultaPartesCuerpo->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["partesCuerpo" => $partesCuerpo]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>